<?php

namespace Earls\RhinoReportBundle\Module\Table\Definition;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition.
 */
class GroupDefinition extends Definition
{
    protected $parent;
    protected $orderBy = array();
    protected $groupBy;

    /**
     * Give the position of the group into its parent.
     */
    protected $itemOrder;

    /**
     * @var ArrayCollection
     *                      Contains Groups and Rows
     */
    protected $items;
    protected $rowSpans = array();
    protected $actions = array();
    protected $groupAction = null;
    protected $extendingGroupAction = false;
    protected $conditionalFormattings = array();

    public function __construct($displayId)
    {
        $this->setDisplayId($displayId);
        $this->items = new ArrayCollection();
    }

    protected function setItemsOrder()
    {
        $count = 0;
        foreach ($this->items as $item) {
            $item->setItemOrder($count);
            ++$count;
        }
    }

    public function addGroup($displayId)
    {
        $group = new self($displayId);
        $group->setParent($this);
        $this->addItem($group);

        return $group;
    }

    public function addRow(array $options)
    {
        $row = new RowDefinition($options);
        $row->setParent($this);
        $this->addItem($row);

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
            'dependences' => $dependences,
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
            'dependences' => $dependences,
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
            'arg' => $arg,
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

    public function getItem($displayId)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('displayId', $displayId))
        ;

        $item = $this->items->matching($criteria);
        $item = ($item->count() > 0) ? $item[0] : null;

        return $item;
    }

    protected function addItem($item, $runOrder = true)
    {
        $this->getItems()->add($item);
        if ($runOrder) {
            $this->setItemsOrder();
        }

        return $this;
    }

    public function setItems($items)
    {
        $this->getItems()->clear();
        foreach ($items as $item) {
            $this->addItem($item, false);
        }
        $this->setItemsOrder();
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setParent($parent)
    {
        if (!$parent instanceof TableDefinition && !$parent instanceof self) {
            throw new UnexpectedTypeException($parent, 'Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition Or Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition');
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

    public function getConditionalFormattings()
    {
        return $this->conditionalFormattings;
    }

    public function addConditionalFormatting($selectedColumn, array $displayIds, $condition, array $classes)
    {
        $conditionalFormatting = array(
            'selectedColumn' => $selectedColumn,
            'displayIds' => $displayIds,
            'condition' => $condition,
            'classes' => $classes,
        );
        $this->conditionalFormattings[] = $conditionalFormatting;

        return $this;
    }

    public function getRows()
    {
        return array_filter($this->getItems()->toArray(), function ($item) {
            return $item instanceof RowDefinition;
        });
    }

    public function getGroups()
    {
        return array_filter($this->getItems()->toArray(), function ($item) {
            return $item instanceof GroupDefinition;
        });
    }

    public function getItemOrder()
    {
        return $this->itemOrder;
    }

    public function setItemOrder($num)
    {
        $this->itemOrder = $num;

        return $this;
    }
}
