<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Earls\RhinoReportBundle\Report\Definition\ReportDefinitionBuilder
 */
class ReportDefinitionBuilder extends AbstractDefinitionBuilder
{

    protected $builders = array();
    protected $instanceBuilders;

    public function __construct($definitionClass)
    {
        $this->instanceBuilders = new ArrayCollection();
        parent::__construct($definitionClass);
    }

    public function advancedTable($id = null)
    {
        return $this->startInstanceBuilder('advanced_table', $id);
    }
    
    public function table($id = null)
    {
        return $this->startInstanceBuilder('table', $id);
    }

    public function bar($id = null)
    {
        return $this->startInstanceBuilder('bar', $id);
    }

    protected function startInstanceBuilder($type, $id = null)
    {
        $instanceDefinitionBuilder = clone $this->getBuilder($type);
        $instanceDefinitionBuilder->setParent($this);
        $instanceDefinitionBuilder->setId($id);
        
        $this->addInstanceBuilder($instanceDefinitionBuilder);

        return $instanceDefinitionBuilder;
    }

    public function build()
    {
        foreach ($this->getInstanceBuilders() as $instance) {
            $instance->build();
            $this->getDefinition()->addItem($instance->getItemBuild());
        }

        return $this;
    }

    public function addBuilder($type, $builder)
    {
        $this->builders[$type] = $builder;
        return $this;
    }

    public function setBuilders(array $builders)
    {
        foreach ($builders as $key => $builder) {
            $this->addBuilder($key, $builder);
        }
        return $this;
    }

    /**
     * 
     * @param string $type
     * @return AbstractDefinitionBuilder
     */
    public function getBuilder($type)
    {
        if (!isset($this->builders[$type])) {
            throw new \Exception(sprintf('`%s` builder doesn\'t exist', $type));
        }
        return $this->builders[$type];
    }

    public function getInstanceBuilders()
    {
        return $this->instanceBuilders;
    }

    public function addInstanceBuilder(AbstractDefinitionBuilder $instanceBuilders)
    {
        $this->instanceBuilders[] = $instanceBuilders;
        
        return $this;
    }

}
