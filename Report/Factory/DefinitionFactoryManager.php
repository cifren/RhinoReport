<?php

namespace Earls\RhinoReportBundle\Report\Factory;

/**
 * Earls\RhinoReportBundle\Report\Factory\DefinitionFactoryManager
 * 
 * Set Object factory for each child
 * 
 * @author cifren
 */
class DefinitionFactoryManager
{
    protected $factory;
    protected $childrenFactories = array();
    
    public function setObjectFactory($entity)
    {
        foreach($entity->getItems() as $item){
            if(array_key_exists($item->getFactoryType(), $this->childrenFactories)){
                $item->setObjectFactory($this->childrenFactories[$item->getFactoryType()]);
            } else {
                throw new \Exception(sprintf(
                    "This factory type '%s' doesn't exist, factory type available: '%s'",
                    $item->getFactoryType(),
                    join("', '", array_keys($this->childrenFactories))
                ));
            }
        }
    }
    
    public function addFactory($key, $factory)
    {
        $this->childrenFactories[$key] = $factory;
        return $this;
    }
}