<?php

namespace Fuller\ReportBundle\Definition;

use Fuller\ReportBundle\Definition\ReportDefinition;
use Fuller\ReportBundle\Definition\Table\TableDefinitionBuilder;

/**
 * Fuller\ReportBundle\Definition\ReportDefinitionBuilder
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
