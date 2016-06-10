<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Earls\RhinoReportBundle\Report\Definition\ModuleDefinition.
 *
 * Description of ModuleDefinition
 *
 * @author cifren
 */
abstract class ModuleDefinition implements ReportDefinitionInterface, ModuleDefinitionInterface
{
    protected $parent;
    protected $position;
    protected $template = 'DefaultTemplate';
    protected $moduleType = 'default';
    protected $factoryType;

    public function getDisplayId()
    {
        return $this->displayId;
    }

    public function setDisplayId($displayId)
    {
        $this->displayId = $displayId;

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

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function setModuleType($type)
    {
        $this->moduleType = $type;
    }

    public function getModuleType()
    {
        return $this->moduleType;
    }

    public function getFactoryType()
    {
        if ($this->getModuleType()) {
            $this->factoryType = $this->getModuleType();
        }

        return $this->factoryType;
    }

    public function setFactoryType($factoryType)
    {
        $this->factoryType = $factoryType;

        return $this;
    }
}
