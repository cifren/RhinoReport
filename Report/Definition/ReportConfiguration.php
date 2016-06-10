<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use Earls\RhinoReportBundle\Report\ReportObject\Report;

/**
 * Earls\RhinoReportBundle\Report\Definition\ReportConfiguration.
 */
class ReportConfiguration implements ReportConfigurationInterface
{
    protected $reportDefinitionBuilder;
    protected $filter;
    protected $reportDefinition;
    protected $queryBuilder;
    protected $data;

    /**
     * {@inheritdoc}
     */
    public function getFilter()
    {
        return $this->filter ? $this->filter : $this->getDefinitionFilter();
    }

    public function hasFilter()
    {
        if ($this->getFilter()) {
            return true;
        } else {
            return false;
        }
    }

    protected function getDefinitionFilter()
    {
        return $this->reportDefinition ? $this->reportDefinition->getFilters() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterModel()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigReportDefinition(Request $request, $dataFilter)
    {
        return $this->reportDefinition;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfigReportDefinition(ReportDefinitionInterface $reportDefinition)
    {
        $this->reportDefinition = $reportDefinition;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getArrayData(array $data, $dataFilter)
    {
        $data = $this->initArrayData($data, $dataFilter);

        return $data;
    }

    /**
     * Apply a closure or replace $data.
     *
     * @param   $data       data that will be modified
     * @param   $datafilter filter request
     *
     * @return array Modified data
     */
    protected function initArrayData($data, $datafilter = null)
    {
        if (is_object($this->data) && ($this->data instanceof Closure)) {
            $data = $this->data($data, $datafilter);
        } elseif (is_array($this->data) && !empty($this->data)) {
            $data = $this->data;
        }

        return $data;
    }

    /**
     * Set an array that will replace the one defined
     *  or a Closure that will modified the current array.
     *
     * @param $data Closure or Array
     *
     * @return Closure or Array
     */
    public function setArrayData($data)
    {
        $this->data = $data;

        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getReportObject(Report $object, $dataFilter)
    {
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilder($dataFilter)
    {
        return $this->queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        return $this->queryBuilder;
    }

    public function hasQueryBuilder($dataFilter)
    {
        if ($this->getQueryBuilder($dataFilter)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return ReportDefinitionBuilder
     */
    public function getReportDefinitionBuilder()
    {
        return $this->reportDefinitionBuilder;
    }

    public function setReportDefintionBuilder(AbstractDefinitionBuilder $reportDefinitionBuilder)
    {
        $this->reportDefinitionBuilder = $reportDefinitionBuilder;

        return $this;
    }

    public function getDefaultOptions()
    {
        return array(
            'template' => 'DefaultTemplate',
            'availableExport' => array('html' => 'Display onscreen'),
            'ajaxEnabled' => true,
        );
    }

    public function getOptions($defautOptions)
    {
        return $defautOptions;
    }

    public function getResolvedOptions()
    {
        return $this->getOptions($this->getDefaultOptions());
    }
}
