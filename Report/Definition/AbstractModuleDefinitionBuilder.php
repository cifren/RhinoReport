<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Earls\RhinoReportBundle\Report\Definition\AbstractModuleDefinitionBuilder
 */
abstract class AbstractModuleDefinitionBuilder extends AbstractDefinitionBuilder
{

    protected $id;
    protected $parent;

    public function __construct(ModuleDefinitionInterface $definition)
    {
        parent::__construct($definition);
    }

    public function setParent(DefinitionBuilderInterface $parent)
    {
        $this->parent = $parent;
        $this->getDefinition()->setParent($parent->getDefinition());

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if(!$id){
            $id = uniqid();
        }
        $this->id = $id;
        $this->getDefinition()->setDisplayId($id);

        return $this;
    }

}
