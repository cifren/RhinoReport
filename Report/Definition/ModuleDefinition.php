<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Description of ModuleDefinition
 *
 * @author cifren
 */
abstract class ModuleDefinition
{

    protected $factoryServiceName = "";

    public function __construct($factoryServiceName = "")
    {
        $this->factoryServiceName = $factoryServiceName;
    }

    public function setFactoryServiceName($factoryServiceName)
    {
        $this->factoryServiceName = $factoryServiceName;

        return $this;
    }

    public function getFactoryServiceName()
    {
        return $this->factoryServiceName;
    }

}
