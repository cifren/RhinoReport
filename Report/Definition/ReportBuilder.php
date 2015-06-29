<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormTypeInterface;
use Earls\RhinoReportBundle\Report\Definition\ReportConfigurationInterface;
use Earls\RhinoReportBundle\Module\Table\Util\DataObject;
use Earls\RhinoReportBundle\Report\ReportObject\Report;
use Earls\RhinoReportBundle\Report\ReportObject\Filter;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Earls\RhinoReportBundle\Report\Filter\ReportFilterInterface;

/**
 * Earls\RhinoReportBundle\Report\Definition\ReportBuilder
 */
class ReportBuilder
{

    /**
     *
     * @var Report
     */
    protected $report = null;
    protected $filterForm;
    protected $rptConfig;
    protected $formFactory;
    protected $container;
    protected $lexikFormFilter;
    protected $request;
    protected $filterType;
    protected $reportDefinition;

    public function __construct(ReportConfigurationInterface $rptConfig, ContainerInterface $container, $lexikFormFilter, $request, $formFactory)
    {
        $this->rptConfig = $rptConfig;
        $this->formFactory = $formFactory;
        $this->container = $container;
        $this->lexikFormFilter = $lexikFormFilter;
        $this->request = $request;
        $this->filterType = $this->rptConfig->getFilter();
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

        $reportfactory = $this->container->get($this->reportDefinition->getFactoryServiceName());
        $reportfactory->setDefinition($this->reportDefinition);
        $reportfactory->setData($dataObject);
        $reportfactory->build();

        $this->report = $reportfactory->getItem();
        $this->report = $this->rptConfig->getReportObject($this->report, $dataFilter);
        $this->report->setOptions($this->rptConfig->getResolvedOptions());
        //$this->buildExport();
        //set filter
        if ($this->rptConfig->getFilter()) {
            $filter = new Filter();
            $filter->setForm($this->getFilterForm());
            $filter->setOptions($this->rptConfig->getResolvedOptions());
            $this->report->setFilter($filter);
        }
    }

    /**
     * build only filter form empty
     *
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

            echo "<h1>Rhino data debug</h1>";
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

    protected function getFactory()
    {
        
    }

    public function getFilterQuery($queryBuilder)
    {
        if ($this->filterType instanceof FormTypeInterface) {
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
        if (!$this->filterType) {
            return null;
        }

        if ($this->filterForm) {
            return $this->filterForm;
        }

        if (!$this->filterType instanceof ReportFilterInterface) {
            throw new UnexpectedTypeException($this->filterType, 'Earls\RhinoReportBundle\Report\Filter\ReportFilterInterface');
        }

        //-- FILTER FORM
        $this->filterForm = $this->formFactory->create($this->filterType, $this->rptConfig->getFilterModel());

        if ($this->request->get($this->filterType->getName())) {
            // bind values from the request
            $this->filterForm->bind($this->request);
        } elseif (!$this->rptConfig->getFilterModel()) {
            $this->filterForm->bind($this->filterType->getDefaultBind());
        }

        return $this->filterForm;
    }

}
