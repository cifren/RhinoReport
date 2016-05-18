<?php

namespace Earls\RhinoReportBundle\Module\Bar\Definition;

use Earls\RhinoReportBundle\Report\Definition\AbstractModuleDefinitionBuilder;

/**
 * Earls\RhinoReportBundle\Module\Bar\Definition\BarDefinitionBuilder
 */
class BarDefinitionBuilder extends AbstractModuleDefinitionBuilder
{

    public function label($columnName)
    {
        $this->getDefinition()->setLabelColumn($columnName);

        return $this;
    }

    public function dataset($columnData, $labelData, $options = array())
    {
        $dataset = new DatasetDefinition($columnData, $labelData, $options);
        
        $this->getDefinition()->addDataset($dataset);

        return $this;
    }

    public function position($position)
    {
        $this->getCurrentDefinition()->setPosition($position);

        return $this;
    }
    
    public function template($name)
    {
        $this->getCurrentDefinition()->setTemplate($name);

        return $this;
    }

    public function end()
    {
        if ($this->getCurrentDefinition() instanceof BarDefinition) {
            return $this->parent;
        }
        $this->setCurrentDefinition($this->getCurrentDefinition()->end());

        return $this;
    }

    public function build()
    {
        return $this->getDefinition();
    }

}
