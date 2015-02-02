<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Earls\RhinoReportBundle\Report\Definition\AbstractDefinitionBuilder
 */
abstract class AbstractDefinitionBuilder implements DefinitionBuilderInterface
{

    protected $id;
    protected $parent;
    protected $definitionClass;
    protected $definition;
    protected $currentDefinition;

    public function __construct($definitionClass)
    {
        $this->definitionClass = $definitionClass;
    }

    public function getItemBuild()
    {
        return $this->getDefinition();
    }

    public function build()
    {
        return $this;
    }

    public function setParent(DefinitionBuilderInterface $parent)
    {
        $this->parent = $parent;
        $this->getDefinition()->setParent($parent->getDefinition());

        return $this;
    }

    /**
     * 
     * @return AbstractDefinitionBuilder
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function buildDefinition()
    {
        $definitionClass = $this->getDefinitionClass();
        
        return new $definitionClass();
    }

    public function getDefinition()
    {
        if (!$this->definition) {
            $this->definition = $this->buildDefinition();
        }

        return $this->definition;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        $this->getDefinition()->setId($id);

        return $this;
    }

    public function getDefinitionClass()
    {
        return $this->definitionClass;
    }

    public function getCurrentDefinition()
    {
        if (!$this->currentDefinition) {
            $this->currentDefinition = $this->getDefinition();
        }

        return $this->currentDefinition;
    }

    public function setDefinitionClass($definitionClass)
    {
        $this->definitionClass = $definitionClass;
        return $this;
    }

    public function setCurrentDefinition($currentDefinition)
    {
        $this->currentDefinition = $currentDefinition;
        return $this;
    }

}
