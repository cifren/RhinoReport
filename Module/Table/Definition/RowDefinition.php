<?php

namespace Earls\RhinoReportBundle\Module\Table\Definition;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition
 *
 */
class RowDefinition extends Definition
{

    /**
     * Give the position of the row into its parent 
     */
    protected $itemOrder;
    protected $columnDefinitions;
    protected $colSpans;
    protected $options;
    protected $actions = array();
    protected $groupAction = null;
    protected $extendingGroupAction = false;

    public function __construct(array $options)
    {
        $this->options['unique'] = isset($options['unique']) ? $options['unique'] : false;
        $this->columnDefinitions = new ArrayCollection();
    }

    public function createAndAddColumn($displayId, $type, $dataId = null)
    {
        $column = new ColumnDefinition($displayId, $type, $dataId);
        $column->setParent($this);
        
        $this->addColumn($column);

        return $column;
    }
    
    public function setColumns($columns = array())
    {
        $this->getColumns()->clear();
        foreach($columns as $col){
            if(!$this->getColumns()->contains($col)){
                $this->addColumn($col);
            }
        }
        
        return $this;
    }
    
    public function addColumn(ColumnDefinition $columnDefinition)
    {
        $this->columnDefinitions[] = $columnDefinition;
        
        return $columnDefinition;
    }

    public function getColumn($displayId)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("displayId", $displayId))
        ;

        $items = $this->columnDefinitions->matching($criteria);
        
        $item = null;
        if($items->count() > 0){
            $array = array_values($items->toArray());
            $item = array_shift($array);
        }
        
        return $item;
    }

    public function getColumns()
    {
        return $this->columnDefinitions;
    }

  /*  public function setColumn($displayId, ColumnDefinition $column)
    {
        $this->columnDefinitions[$displayId] = $column;

        return $this;
    }*/

    public function setColSpan($displayId, $number)
    {
        $column = $this->getColumn($displayId);
        if (!isset($column)) {
            throw new \UnexpectedValueException('Column \'' . $displayId . '\' in group \'' . $this->getParent()->getDisplayId() . '\' is not yet defined');
        }
        
        $column->setColSpan($number);

        return $this;
    }

    public function setParent($parent)
    {
        if (!$parent instanceof GroupDefinition)
            throw new UnexpectedTypeException($this->parent, 'Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition');
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
        uasort($this->getColumns(), $func);

        return $this;
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
