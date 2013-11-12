<?php

namespace Earls\RhinoReportBundle\Model\Table\ReportObject;

use Earls\RhinoReportBundle\Model\Table\ReportObject\TableObject;
use Earls\RhinoReportBundle\Definition\Table\HeadDefinition;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Table;

/*
 * Earls\RhinoReportBundle\Model\Table\ReportObject\Head
 */

class Head extends TableObject
{

    protected $columns;

    public function __construct($id, HeadDefinition $definition, Table $parent)
    {
        $this->type = 'head';

        parent::__construct($id, $definition, $parent);
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumn($displayId, array $column)
    {
        $this->columns[$displayId] = $column;

        return $this;
    }

    public function setColumns(array $columnNames)
    {
        foreach ($columnNames as $id => $column) {
            $this->setColumn($id, $column);
        }

        return $this;
    }

}
