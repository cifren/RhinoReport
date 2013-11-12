<?php

namespace Earls\RhinoReportBundle\Definition;

use Earls\RhinoReportBundle\Definition\ReportDefinition;
use Earls\RhinoReportBundle\Definition\Table\TableDefinitionBuilder;

/**
 * Earls\RhinoReportBundle\Definition\ReportDefinitionBuilder
 */
class ReportDefinitionBuilder
{

    protected $reportDefinition;

    public function __construct()
    {
        $this->reportDefinition = new ReportDefinition();
    }

    public function table($id = null)
    {
        $tableDefinitionBuilder = new TableDefinitionBuilder($this->reportDefinition, $this, $id);

        $this->reportDefinition->addItem($tableDefinitionBuilder->getTableDefinition());

        return $tableDefinitionBuilder;
    }

    public function build()
    {
        foreach ($this->reportDefinition->getItems() as $item) {
            $item->build();
        }

        return $this->reportDefinition;
    }

}
