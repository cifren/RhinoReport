<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/*
 *  Earls\RhinoReportBundle\Report\Definition\ReportDefinitionInterface
 *
 */

interface ReportDefinitionInterface
{
    public function setFactoryService($serviceName);

    public function getFactoryService();

}
