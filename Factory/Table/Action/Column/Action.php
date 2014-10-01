<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Model\Table\ReportObject\Row;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Column;

/**
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\Action
 *
 */
abstract class Action implements ActionInterface
{

    /**
     *
     * @var Column 
     */
    protected $column;

    /**
     *
     * @var array 
     */
    protected $rowData;

    /**
     *
     * @var Row 
     */
    protected $rowObject;

    /**
     *
     * @var array 
     */
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

    protected function throwIssue($message)
    {
        $groupName = $this->column->getParent()->getParent()->getDefinition()->getId();
        throw new \Exception(sprintf('An error happened on "%s" Action, "%s" column and "%s" group, with message : %s', $this->getName(), $this->column->getId(), $groupName, $message));
    }

    protected function getName()
    {
        return 'unknown';
    }

}
