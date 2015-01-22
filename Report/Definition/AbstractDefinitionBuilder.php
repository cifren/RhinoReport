<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Earls\RhinoReportBundle\Report\Definition\AbstractDefinitionBuilder
 */
abstract class AbstractDefinitionBuilder implements DefinitionBuilderInterface
{

    protected $parent;
    protected $definition;
    protected $id;

    public function __construct($definitionClass)
    {
        $this->definition = new $definitionClass();
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
        $this->definition->setParent($parent->getDefinition());

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

    public function getDefinition()
    {
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

}
