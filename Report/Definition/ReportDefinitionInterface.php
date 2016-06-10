<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 *  Earls\RhinoReportBundle\Report\Definition\ReportDefinitionInterface.
 */
interface ReportDefinitionInterface
{
    public function setObjectFactory($factory);

    public function getObjectFactory();

    public function getDisplayId();
}
