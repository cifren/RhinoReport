<?php

namespace Earls\RhinoReportBundle\Factory\Table\GroupAction\Column;

use Earls\RhinoReportBundle\Model\Table\ReportObject\Table;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Column;

/**
 *  Earls\RhinoReportBundle\Factory\Table\GroupAction\Column\GroupAction
 *
 */
abstract class GroupAction implements GroupActionInterface
{

    protected $column;
    protected $table;
    protected $options;
    protected $dependences = array();

    public function setParameters(Column $column, Table $table, array $arguments)
    {
        $this->column = $column; // Column object from where action is executed
        $this->table = $table;

        $this->setOptions($arguments);

        return $this;
    }

    public function setOptions(array $arguments)
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
        throw new \Exception('did you forget to declare `setData()` in your groupAction Class ?');
    }

    public function getDependences()
    {
        return $this->dependences;
    }

    public function setDependences(array $dependences)
    {
        $this->dependences = $dependences;

        return $this;
    }

}
