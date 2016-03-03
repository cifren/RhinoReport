<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use Earls\RhinoReportBundle\Report\ReportObject\Report;

/*
 * Earls\RhinoReportBundle\Report\Definition\ReportConfigurationInterface
 *
 * Interface of Configuration of report
 *
 */
interface ReportConfigurationInterface
{
    /**
     * Sets filter Type to extract sql from it
     *
     * @return AbstractType In order to create a sql statements
     */
    public function getFilter();

    /**
     * Sets filter Model and use it when create filter form
     *
     * @return AbstractType In order to create a sql statements
     */
    public function getFilterModel();

    /**
     * You can modify array from sql at this moment
     *
     * @param  $data        array               data from queryBuilder before transformation, empty if no getQueryBuilder
     * @param  $dataFilter  object or array     Data from form filter, type depends on getFilter, empty if no getFilter
     *
     * @return array Data after transformation
     */
    public function getArrayData(array $data, $dataFilter);

    /**
     * You can modify report Object after generation of report with data
     *
     * @param  $data        array               data from queryBuilder before transformation, empty if no getQueryBuilder
     * @param  $dataFilter  object or array     Data from form filter, type depends on getFilter, empty if no getFilter
     *
     * @return array Data after transformation
     */
    public function getReportObject(Report $object, $dataFilter);

    /**
     * Configure display for report
     *
     * @return Report
     */
    public function getConfigReportDefinition(Request $request, $dataFilter);
    
    /**
     *  Set the report definition in order to give all the 
     * information about the display
     */
     public function setConfigReportDefinition(ReportDefinitionInterface $reportDefinition);

    /**
     * Give options
     *
     * @return array
     */
    public function getResolvedOptions();
    
    /**
     * Get doctrine query builder 
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder($dataFilter);
    
    public function setQueryBuilder(QueryBuilder $queryBuilder);
}
