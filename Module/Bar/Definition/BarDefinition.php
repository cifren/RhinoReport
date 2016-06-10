<?php

namespace Earls\RhinoReportBundle\Module\Bar\Definition;

use Earls\RhinoReportBundle\Report\Definition\ModuleDefinition;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Earls\RhinoReportBundle\Module\Bar\Definition\BarDefinition
 * 
 * @author francis
 */
class BarDefinition extends ModuleDefinition
{

    protected $options = array();
    protected $labelColumn;
    protected $datasets;
    protected $factory;
    protected $moduleType = 'bar';
    
    public function __construct()
    {
        $this->datasets = new ArrayCollection();
    }
    
    public function setOptions($options)
    {
        $this->options = $options;
        
        return $this;
    }

    public function setLabelColumn($labelColumn)
    {
        $this->labelColumn = $labelColumn;
        
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getLabelColumn()
    {
        return $this->labelColumn;
    }

    public function getDatasets()
    {
        return $this->datasets;
    }

    public function setDatasets(array $datasets)
    {
        foreach($datasets as $dataset){
            $this->addDataset($dataset);
        }
        return $this;
    }

    public function addDataset(DatasetDefinition $dataset)
    {
        $this->datasets->add($dataset);
        
        return $this;
    }

    public function getObjectFactory()
    {
        return $this->factory;
    }

    public function setObjectFactory($factory)
    {
        $this->factory = $factory;
        return $this;
    }

}
