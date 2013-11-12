<?php

namespace Earls\RhinoReportBundle\Definition;

/*
 *  Earls\RhinoReportBundle\Definition\ReportDefinitionInterface
 *
 */

interface ReportDefinitionInterface
{
    public function setFactoryService($serviceName);

    public function getFactoryService();

}
