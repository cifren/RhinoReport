<?php

namespace Fuller\ReportBundle\Definition;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormTypeInterface;
use Fuller\ReportBundle\Definition\ReportConfigurationInterface;
use Fuller\ReportBundle\Util\Table\DataObject;
use Fuller\ReportBundle\Model\Report;

/**
 * Fuller\ReportBundle\Definition\ReportBuilder
 */
class ReportBuilder
{

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

        // initialize a query builder if existing
        if (!$this->rptConfig->hasQueryBuilder()) { //report dont use query builder
            // check to see if a filter exists
            if (!$this->rptConfig->hasFilter()) {
                $dataFilter = array();
            } else {
                //can be object or array
                $dataFilter = $this->getFilterForm()->getData();
            }

            $data = $this->rptConfig->getArrayData(array(), $dataFilter);
        } else { // report use queryBuilder
            $queryBuilder = $this->rptConfig->getQueryBuilder();
            if ($this->rptConfig->hasFilter()) {
                $queryBuilder = $this->getFilterQuery($queryBuilder);
                $dataFilter = $this->getFilterForm()->getData();
            } else {
                $dataFilter = array();
            }

            $data = $queryBuilder->getQuery()->getScalarResult();
            $data = $this->rptConfig->getArrayData($data, $dataFilter);
        }

        $dataObject = new DataObject($data);

        $this->reportDefinition = $this->rptConfig->getConfigReportDefinition($this->request, $dataFilter);

        $reportfactory = $this->container->get($this->reportDefinition->getFactoryService());
        $reportfactory->setDefinition($this->reportDefinition);
        $reportfactory->setData($dataObject);
        $reportfactory->build();

        $this->report = $reportfactory->getItem();
        $this->report = $this->rptConfig->getReportObject($this->report, $dataFilter);
        $this->buildExport();

        //set filter
        if ($this->rptConfig->getFilter())
            $this->report->setFilter($this->getFilterForm());
    }

    /**
     * build only filter form empty
     *
     */
    public function buildFilter()
    {
        $this->report = new Report();

        if ($this->rptConfig->hasFilter()) {
            $this->report->setFilter($this->getFilterForm());
        }
        $this->buildExport();
    }

    protected function buildExport()
    {
        if (!is_array($this->rptConfig->getAvailableExport())) {
            throw new \InvalidArgumentException('Expected array');
        }

        $availableExport = $this->rptConfig->getAvailableExport();
        if (empty($availableExport)) {
            throw new \InvalidArgumentException('Expected at least one export');
        }
        $this->report->setAvailableExport($this->rptConfig->getAvailableExport());
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

        if ($this->filterForm)
            return $this->filterForm;

        //-- FILTER FORM
        $this->filterForm = $this->formFactory->create($this->filterType, $this->rptConfig->getFilterModel());

        if ($this->request->get($this->filterType->getName())) {
            // bind values from the request
            $this->filterForm->bindRequest($this->request);
        } elseif (!$this->rptConfig->getFilterModel()) {
            $this->filterForm->bind($this->filterType->getDefaultBind());
        }

        return $this->filterForm;
    }

}
