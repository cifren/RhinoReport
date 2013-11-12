<?php

namespace Earls\RhinoReportBundle\Definition\Table;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Earls\RhinoReportBundle\Definition\Table\TableDefinition;
use Earls\RhinoReportBundle\Definition\Table\GroupDefinition;

/**
 * Earls\RhinoReportBundle\Definition\Table\GroupDefinition
 *
 */
class GroupDefinition extends Definition
{

    protected $id;
    protected $parent;
    protected $orderBy = array();
    protected $groupBy;
    protected $items;
    protected $rowSpans = array();
    protected $actions = array();
    protected $groupAction = null;
    protected $extendingGroupAction = false;

    public function __construct($id, array $exportConfigs)
    {
        parent::__construct($exportConfigs);
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function addGroup($id, array $exportConfigs)
    {
        $group = new GroupDefinition($id, $exportConfigs);
        $group->setParent($this);
        $this->items[$id] = $group;

        return $group;
    }

    public function addRow(array $options, array $exportConfigs)
    {
        $row = new RowDefinition($options, $exportConfigs);
        $row->setParent($this);
        $this->items[] = $row;

        return $row;
    }

    public function setRowSpans(array $displayIds)
    {
        $this->rowSpans = $displayIds;

        return $this;
    }

    public function getRowSpan($displayId)
    {
        if (isset($this->rowSpans[$displayId])) {
            return $this->rowSpans[$displayId];
        } else {
            return false;
        }
    }

    public function getRowSpans()
    {
        return $this->rowSpans;
    }

    public function setOrderBy(array $orderedColumns)
    {
        $this->orderBy = $orderedColumns;

        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setGroupBy($id)
    {
        $this->groupBy = $id;

        return $this;
    }

    public function getGroupBy()
    {
        return $this->groupBy;
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

    public function getItem($id)
    {
        return $this->items[$id];
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setParent($parent)
    {
        if (!$parent instanceof TableDefinition && !$parent instanceof GroupDefinition) {
            throw new UnexpectedTypeException($parent, 'Earls\RhinoReportBundle\Definition\Table\TableDefinition Or Earls\RhinoReportBundle\Definition\Table\GroupDefinition');
        }

        parent::setParent($parent);
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getHead()
    {
        return $this->getParent()->getHead();
    }

}
