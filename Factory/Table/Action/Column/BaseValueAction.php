<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Factory\Table\Action\Column\Action;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Row;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Column;

/**
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\BaseValueAction
 *
 */
class BaseValueAction extends Action
{

    //enable modification object column from action
    public function setParameters(Column $column, array $rowData, Row $rowObject, array $arguments)
    {
        $this->column = $column; // Column index value
        $this->rowData = $rowData; // Array of the raw row data
        $this->rowObject = $rowObject; // Display row to the screen

        $this->setOptions($arguments);

        return $this;
    }

    public function setData()
    {
        if ($this->options['dataId']) {
            $data = $this->rowData[$this->options['dataId']];
        } elseif ($this->options['displayId']) {
            $data = $this->rowObject->getColumn($this->options['displayId'])->getData();
        } else {
            $data = $this->column->getData();
        }

        $this->column->setBaseValue($data);

        return $this->column->getData();
    }

    public function getOptions()
    {
        return array(
            'dataId' => null,
            'displayId' => null,
        );
    }

}
