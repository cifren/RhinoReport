<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Earls\RhinoReportBundle\Report\Definition\AbstractDefinitionBuilder
 */
abstract class AbstractDefinitionBuilder implements DefinitionBuilderInterface
{

    protected $definition;
    protected $currentDefinition;
    protected $isBuild = false;

    public function __construct(ReportDefinitionInterface $definition)
    {
        $this->setDefinition($definition);
    }

    public function getBuildItem()
    {
        if(!$this->isBuild){
            $this->build();
            $this->isBuild();
        }
        return $this->getDefinition();
    }

    public function isBuild()
    {
        $this->isBuild = true;
        return $this;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function setDefinition($definition)
    {
        $this->definition = $definition;
        return $this;
    }

    public function getCurrentDefinition()
    {
        if (!$this->currentDefinition) {
            $this->currentDefinition = $this->getDefinition();
        }

        return $this->currentDefinition;
    }

    public function setCurrentDefinition($currentDefinition)
    {
        $this->currentDefinition = $currentDefinition;
        return $this;
    }

}
