<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Symfony\Component\HttpFoundation\Request;
use Earls\RhinoReportBundle\Report\ReportObject\Report;

/**
 * Earls\RhinoReportBundle\Report\Definition\ReportConfiguration
 */
abstract class ReportConfiguration implements ReportConfigurationInterface
{

    protected $reportDefinitionBuilder;

    public function __construct($reportDefinitionBuilder)
    {
        $this->reportDefinitionBuilder = $reportDefinitionBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilter()
    {
        return null;
    }

    public function hasFilter()
    {
        if ($this->getFilter()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getFilterModel()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigReportDefinition(Request $request, $dataFilter)
    {
        
    }

    /**
     * {@inheritDoc}
     */
    public function getArrayData(array $data, $dataFilter)
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getReportObject(Report $object, $dataFilter)
    {
        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryBuilder()
    {
        return null;
    }

    public function hasQueryBuilder()
    {
        if ($this->getQueryBuilder()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @return ReportDefinitionBuilder
     */
    public function getReportDefinitionBuilder()
    {
        return $this->reportDefinitionBuilder;
    }

    public function setReportDefinitionBuilder($reportDefinitionBuilder)
    {
        $this->reportDefinitionBuilder = $reportDefinitionBuilder;
        return $this;
    }
    
    public function getDefaultOptions(){
        return array(
            'availableExport' => array('html' => 'Display onscreen'),
            'ajaxEnabled' => true
        );
    }
    
    public function getOptions($defautOptions){
        return $defautOptions;
    }
    
    public function getResolvedOptions(){
        return $this->getOptions($this->getDefaultOptions());
    }

}
