<?php

namespace Earls\RhinoReportBundle\Report\ReportObject;

/**
 * Description of ModuleObject.
 *
 * @author cifren
 */
abstract class ModuleObject
{
    protected $id;
    protected $definition;
    protected $options = array();

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getPosition()
    {
        return $this->getDefinition()->getPosition();
    }

    public function getTemplate()
    {
        return $this->getDefinition()->getTemplate();
    }

    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function getType()
    {
        return 'noType';
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }
}
