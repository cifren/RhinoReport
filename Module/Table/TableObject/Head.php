<?php

namespace Earls\RhinoReportBundle\Module\Table\TableObject;

use Earls\RhinoReportBundle\Module\Table\TableObject\TableObject;
use Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition;
use Earls\RhinoReportBundle\Module\Table\TableObject\Table;

/*
 * Earls\RhinoReportBundle\Module\Table\TableObject\Head
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
