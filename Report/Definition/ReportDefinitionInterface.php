<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/*
 *  Earls\RhinoReportBundle\Report\Definition\ReportDefinitionInterface
 *
 */

interface ReportDefinitionInterface
{

    public function setFactoryServiceName($factoyrServiceName);

    public function getFactoryServiceName();
}
