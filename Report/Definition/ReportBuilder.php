<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Collection;
use Earls\RhinoReportBundle\Module\Table\Util\DataObject;
use Earls\RhinoReportBundle\Report\ReportObject\Report;
use Earls\RhinoReportBundle\Report\ReportObject\Filter;
use Earls\RhinoReportBundle\Report\Filter\ReportFilterInterface;

/**
 * Earls\RhinoReportBundle\Report\Definition\ReportBuilder.
 */
class ReportBuilder
{
    /**
     * @var Report
     */
    protected $report = null;
    protected $filterForm;
    protected $rptConfig;
    protected $formFactory;
    protected $container;
    protected $lexikFormFilter;
    protected $request;
    protected $reportDefinition;
    protected $reportObjectFactoryCollection;

    public function __construct($lexikFormFilter, $formFactory)
    {
        $this->formFactory = $formFactory;
        $this->lexikFormFilter = $lexikFormFilter;
    }

    public function setConfiguration(ReportConfigurationInterface $config)
    {
        $this->rptConfig = $config;

        return $this;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function build()
    {
        $data = array();
        if (!$this->rptConfig->hasFilter()) {
            $dataFilter = array();
        } else {
            //can be object or array
            $dataFilter = $this->getFilterForm()->getData();
        }

        // initialize a query builder if existing
        if (!$this->rptConfig->hasQueryBuilder($dataFilter)) { //report dont use query builder
            // check to see if a filter exists
            $data = array();
        } else { // report use queryBuilder
            $queryBuilder = $this->rptConfig->getQueryBuilder($dataFilter);
            if (!empty($dataFilter)) {
                $queryBuilder = $this->getFilterQuery($queryBuilder);
            }

            $data = $queryBuilder->getQuery()->getScalarResult();
        }

        $dataAlterated = $this->rptConfig->getArrayData($data, $dataFilter);

        $this->runDebugData($dataAlterated, $dataFilter);

        $dataObject = new DataObject($dataAlterated);

        $this->reportDefinition = $this->rptConfig->getConfigReportDefinition($this->request, $dataFilter);

        $reportfactory = $this->reportDefinition->getObjectFactory();
        $reportfactory->setDefinition($this->reportDefinition);
        $reportfactory->setData($dataObject);
        $reportfactory->build();

        $this->report = $reportfactory->getItem();
        $this->report = $this->rptConfig->getReportObject($this->report, $dataFilter);
        $this->report->setOptions($this->rptConfig->getResolvedOptions());

        //set filter
        if ($this->rptConfig->getFilter()) {
            $filter = new Filter();
            $filter->setForm($this->getFilterForm());
            $filter->setOptions($this->rptConfig->getResolvedOptions());
            $this->report->setFilter($filter);
        }
    }

    /**
     * build only filter form empty.
     */
    public function buildFilter()
    {
        $this->report = new Report();
        $this->report->setOptions($this->rptConfig->getResolvedOptions());

        if ($this->rptConfig->hasFilter()) {
            $filter = new Filter();
            $filter->setForm($this->getFilterForm());
            $filter->setOptions($this->rptConfig->getResolvedOptions());
            $this->report->setFilter($filter);
        }
        //$this->buildExport();
    }

    protected function buildExport()
    {
        if (!is_array($this->rptConfig->getResolvedOptions()['availableExport'])) {
            throw new \InvalidArgumentException('Expected array');
        }

        $options = $this->rptConfig->getResolvedOptions()['availableExport'];
        if (empty($options[''])) {
            throw new \InvalidArgumentException('Expected at least one export');
        }
        $this->report->setOptions($options);
    }

    protected function runDebugData($data, $dataFilter)
    {
        $flag = 'rhino-data-debug';
        $flagValue = isset($_GET[$flag]) ? $_GET[$flag] : null;
        if ($flagValue) {
            $arrayhelper = new \Earls\RhinoReportBundle\Model\Helper\ArrayHelper();

            echo '<h1>Rhino data debug</h1>';
            if (isset($data[$flagValue])) {
                echo $arrayhelper->displayArrayDebug($data[$flagValue]);
            } else {
                try {
                    echo $arrayhelper->displayArrayDebug($data);
                } catch (\Exception $e) {
                    throw new \Exception("Did you select give me what I want ? In url '?rhino-data-debug=true' or '?rhino-data-debug=tableName', issue was '{$e->getMessage()}'");
                }
            }

            die();
        }
    }

    public function getFilterQuery($queryBuilder)
    {
        if ($this->getFilterType() instanceof FormTypeInterface) {
            $filterForm = $this->getFilterForm();

            // build the query from the given filterForm object
            $queryBuilder = $this->lexikFormFilter->addFilterConditions($filterForm, $queryBuilder);
        }

        return $queryBuilder;
    }

    public function getReport()
    {
        return $this->report;
    }

    public function getArray($fullColumn = false, $mergeColumnName = false)
    {
        if (!$this->report) {
            return null;
        }

        return $this->report->getArray();
    }

    public function getHtmlArray()
    {
        if (!$this->report) {
            return null;
        }

        return $this->report->getHtmlArray();
    }

    public function getFilterForm()
    {
        // check if the configuration has a filter
        if (!$this->getFilterType()) {
            return null;
        }

        // check if the form has already been created
        if ($this->filterForm) {
            return $this->filterForm;
        }
        $this->filterForm = $this->createForm($this->getFilterType());

        return $this->filterForm;
    }

    protected function createForm($filterDef)
    {
        switch (true) {
            case $filterDef instanceof Collection:
                return $this->createFormWithEntity($filterDef);
            case $filterDef instanceof ReportFilterInterface:
                return $this->createFormWithType($filterDef);
            default:
                throw new \Exception('Could not create the filter');
        }
    }

    protected function createFormWithEntity($filterEntities)
    {
        $form = $this->formFactory->createBuilder(FormType::class, $this->rptConfig->getFilterModel());

        foreach ($filterEntities as $item) {
            $form->add($item->getName(), $item->getType(), $item->getOptions());
        }

        return $form->getForm();
    }

    protected function createFormWithType($type)
    {
        //-- FILTER FORM
        $filterForm = $this->formFactory->create(get_class($type), $this->rptConfig->getFilterModel());

        if ($this->getRequest($type->getName())) {
            // bind values from the request
            $filterForm->submit($this->getRequest());
        } elseif (!$this->rptConfig->getFilterModel()) {
            $filterForm->submit($type->getDefaultBind());
        }

        return $filterForm;
    }

    protected function getFilterType()
    {
        return $this->rptConfig->getFilter();
    }

    protected function getRequest($key)
    {
        return $this->request->get($key);
    }
}
