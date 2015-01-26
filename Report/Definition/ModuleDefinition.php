<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Description of ModuleDefinition
 *
 * @author cifren
 */
abstract class ModuleDefinition implements ReportDefinitionInterface
{

    protected $factoryServiceName = "";
    protected $id;
    protected $parent;
    protected $position;

    public function __construct($factoryServiceName = "", $id = 'module')
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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        
        return $this;
    }

}
