<?php

namespace Earls\RhinoReportBundle\Module\Bar\Definition;

use Earls\RhinoReportBundle\Report\Definition\AbstractDefinitionBuilder;

/**
 * Earls\RhinoReportBundle\Module\Bar\Definition\BarDefinitionBuilder
 */
class BarDefinitionBuilder extends AbstractDefinitionBuilder
{

    public function __construct($definitionClass)
    {
        parent::__construct($definitionClass);

        $this->currentDefinition = $this->definition;
    }

    public function labels($columnName)
    {
        $this->getDefinition()->setLabelColumn($columnName);

        return $this;
    }

    public function data(array $columnNames)
    {
        $this->getDefinition()->setDataColumns($columnNames);

        return $this;
    }

    public function end()
    {
        if ($this->currentDefinition instanceof BarDefinition) {
            return $this->parent;
        }
        $this->currentDefinition = $this->currentDefinition->end();

        return $this;
    }

    public function build()
    {
        return $this->getDefinition();
    }

}
