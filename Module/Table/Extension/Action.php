<?php

namespace Earls\RhinoReportBundle\Module\Table\Extension;

use Earls\RhinoReportBundle\Module\Table\Extension\ActionInterface;

/*
 *  Earls\RhinoReportBundle\Module\Table\Extension\Action
 *
 */

abstract class Action implements ActionInterface
{

    protected $column;
    protected $rowData;
    protected $rowObject;
    protected $options;

    public function setParameters($column, $rowData, $rowObject, $arguments)
    {
        $this->column = $column; // Column index value
        $this->rowData = $rowData; // Array of the raw row data
        $this->rowObject = $rowObject; // Display row to the screen

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

    public function getData()
    {
        return $this->column->getData();
    }

}
