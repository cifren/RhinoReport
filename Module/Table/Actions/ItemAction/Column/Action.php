<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

use Earls\RhinoReportBundle\Module\Table\TableObject\Row;
use Earls\RhinoReportBundle\Module\Table\TableObject\Column;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\Action
 *
 */
abstract class Action implements ActionInterface
{

    protected $column;
    protected $rowData;
    protected $rowObject;
    protected $options;

    public function setParameters(Column $column, array $rowData, Row $rowObject, array $arguments)
    {
        $this->column = clone $column; // Column index value
        $this->rowData = $rowData; // Array of the raw row data
        $this->rowObject = clone $rowObject; // Display row to the screen

        $this->setOptions($arguments);

        return $this;
    }

    public function setOptions($arguments)
    {
        $this->options = $this->getOptions();
        $this->options = $arguments + $this->options;

        return $this;
    }

    public function getOptions()
    {
        return array();
    }

    public function setData()
    {
        throw new \Exception('did you forget to declare `setData()` in your Action Class `' . get_class($this) . '` ?');
    }

}
