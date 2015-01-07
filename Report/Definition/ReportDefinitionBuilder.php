<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Earls\RhinoReportBundle\Report\Definition\ReportDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\TableDefinitionBuilder;

/**
 * Earls\RhinoReportBundle\Report\Definition\ReportDefinitionBuilder
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
