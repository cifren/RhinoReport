<?php

namespace Fuller\ReportBundle\Definition\Table;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Fuller\ReportBundle\Definition\Table\GroupDefinition;
use Fuller\ReportBundle\Definition\Table\ColumnDefinition;

/*
 * Fuller\ReportBundle\Definition\Table\RowDefinition
 *
 */

class RowDefinition extends Definition
{

    protected $columns;
    protected $colSpans;
    protected $options;
    protected $actions = array();
    protected $groupAction = null;
    protected $extendingGroupAction = false;

    public function __construct(array $options, array $exportConfigs)
    {
        parent::__construct($exportConfigs);
        $this->options['unique'] = isset($options['unique']) ? $options['unique'] : false;
    }

    public function setColumn($displayId, $type, array $exportConfigs, $dataId = null)
    {
        $column = new ColumnDefinition($displayId, $type, $exportConfigs, $dataId);
        $column->setParent($this);
        $this->columns[$displayId] = $column;

        return $this;
    }

    public function getColumn($displayId)
    {
        if (!isset($this->columns[$displayId]))
            return null;
        return $this->columns[$displayId];
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumnItem($displayId, ColumnDefinition $column)
    {
        $this->columns[$displayId] = $column;

        return $this;
    }

    public function setColSpan($displayId, $number)
    {
        if (!isset($this->columns[$displayId])) {
            throw new \UnexpectedValueException('Column \'' . $displayId . '\' in group \'' . $this->getParent()->getId() . '\' is not yet defined');
        }
        $col = $this->columns[$displayId];
        $col->setColSpan($number);

        return $this;
    }

    public function setParent($parent)
    {
        if (!$parent instanceof GroupDefinition)
            throw new UnexpectedTypeException($this->parent, 'Fuller\ReportBundle\Definition\GroupDefinition');
        parent::setParent($parent);
    }

    public function getPath()
    {
        if ($this->path)
            return $this->path;
        return $this->path = $this->parent->getPath();
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setOption($parameter, $value)
    {
        $this->options[$parameter] = $value;

        return $this;
    }

    public function getOption($parameter)
    {
        return $this->options[$parameter];
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setGroupAction($name, array $arg, $dependences = array())
    {
        $this->groupAction = array(
            'name' => $name,
            'arg' => $arg,
            'dependences' => $dependences
        );
        //execute either extendingGroupAction or groupAction
        $this->extendingGroupAction = null;

        return $this;
    }

    public function getGroupAction()
    {
        return $this->groupAction;
    }

    public function hasGroupAction()
    {
        return $this->groupAction != null;
    }

    public function setExtendingGroupAction($dependences = array())
    {
        $this->extendingGroupAction = array(
            'dependences' => $dependences
        );
        //execute either extendingGroupAction either groupAction
        $this->groupAction = null;

        return $this;
    }

    public function getExtendingGroupAction()
    {
        return $this->extendingGroupAction;
    }

    public function hasExtendingGroupAction()
    {
        return $this->extendingGroupAction != null;
    }

    public function addAction($name, array $arg)
    {
        $this->actions[] = array(
            'name' => $name,
            'arg' => $arg
        );
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function hasActions()
    {
        return count($this->actions) > 0;
    }

    public function getHead()
    {
        return $this->getParent()->getHead();
    }

    public function reOrderColumns()
    {
        $func = function ($columnDefinitionA, $columnDefinitionB) {
                    if ($columnDefinitionA == $columnDefinitionB) {
                        return 0;
                    }

                    return ($columnDefinitionA->getOrder() < $columnDefinitionB->getOrder()) ? -1 : 1;
                };
        uasort($this->columns, $func);

        return $this;
    }

}
