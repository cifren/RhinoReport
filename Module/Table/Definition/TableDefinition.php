<?php

namespace Earls\RhinoReportBundle\Module\Table\Definition;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Earls\RhinoReportBundle\Report\Definition\ReportDefinitionInterface;
use Earls\RhinoReportBundle\Report\Definition\ModuleDefinitionInterface;
use Earls\RhinoReportBundle\Report\Definition\ReportDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition
 */
class TableDefinition extends Definition implements ReportDefinitionInterface, ModuleDefinitionInterface
{

    protected $headDefinition;
    protected $bodyDefinition;
    protected $factory;
    protected $parent;
    protected $position;
    protected $template = 'DefaultTemplate';
    protected $moduleType = 'table';
    
    public function setHeadDefinition($headDefinition)
    {
        $this->headDefinition = $headDefinition;
        return $this;
    }

    public function getHeadDefinition()
    {
        if(!$this->headDefinition){
            $this->setHeadDefinition(new HeadDefinition);
            $this->headDefinition->setParent($this);
        }
        return $this->headDefinition;
    }
    
    public function setBodyDefinition($bodyDefinition)
    {
        $this->bodyDefinition = $bodyDefinition;
        return $this;
    }

    public function getBodyDefinition()
    {
        if(!$this->bodyDefinition){
            $this->setBodyDefinition(new GroupDefinition('body'));
            $this->bodyDefinition->setParent($this);
        }
        return $this->bodyDefinition;
    }

    public function setParent($parent)
    {
        if (isset($parent) && !$parent instanceof ReportDefinition) {
            throw new UnexpectedTypeException($parent, 'Earls\RhinoReportBundle\Report\Definition\ReportDefinition');
        }
        parent::setParent($parent);
    }

    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }

        return $this->path = '\\' . $this->excludeSpecialCharacter($this->getDisplayId());
    }

    public function build()
    {
        $this->completeRowDefinition();

        return $this;
    }

    protected function completeRowDefinition()
    {

//        $this->subGroupDefinition($this->bodyDefinition);
    }

    protected function subGroupDefinition($itemDefinition)
    {
        //not used, too complicated stuff, sorry...
        foreach ($itemDefinition->getItems() as $item) {
            if ($item instanceof GroupDefinition) {
                $this->subGroupDefinition($item);
            }

            //compare head to rowDefinition, add column missing, sort new items
            if ($item instanceof RowDefinition) {
                $headColumnDefinitions = $this->headDefinition->getColumns();
                ksort($headColumnDefinitions);

                $itemColumnDefinitions = $item->getColumns();

                $colSpanCounter = 0;

                // array complete with colspan ColumnDefinition
                $newColumnDefinitions = array();

                $tempColumnDefinitions = array();
                foreach ($itemColumnDefinitions as $columnDefinition) {
                    $tempColumnDefinitions[] = $columnDefinition;
                }

                // -1 because count is before
                $columnCount = -1;

                for ($headIndex = 0; $headIndex < count($headColumnDefinitions); $headIndex++) {
                    $columnCount++;

                    if ($tempColumnDefinitions[$columnCount]->getType() == ColumnDefinition::TYPE_DATA) {
                        //head count ignore column type 'data'
                        $headIndex--;

                        //add column
                        $newColumnDefinitions[] = $tempColumnDefinitions[$columnCount];

                        continue;
                    }

                    if ($colSpanCounter != 0) {
                        $colSpanCounter--;

                        //column count ignore column colspan
                        $columnCount--;

                        //definition does'nt exist, need to be created
                        $newColumn = $item->addColumn($headColumnDefinitions[$headIndex]['id'], 'display');

                        //add new column
                        $newColumnDefinitions[] = $newColumn;

                        continue;
                    } else {
                        //add column
                        $newColumnDefinitions[] = $tempColumnDefinitions[$columnCount];
                    }

                    //check the postion of columnDefinition in defintionConfiguration
                    if ($tempColumnDefinitions[$columnCount]->getDisplayId() != $headColumnDefinitions[$headIndex]['id']) {

                        if (!$item->getColumn($headColumnDefinitions[$headIndex]['id'])) {

                            //doesnt exist
                            throw new \UnexpectedValueException('The Column definition \'' . $headColumnDefinitions[$headIndex]['id'] . '\' in group \'' . $item->getParent()->getId() . '\' is missing or does\'nt exist in head, did you forget colspan value to skip this column or did you declare as a type \'columnData\' ?');
                        } else {

                            //exist but is not at the good posistion
                            throw new \UnexpectedValueException('The Column definition \'' . $headColumnDefinitions[$headIndex]['id'] . '\' exist but is in the wrong order in group \'' . $item->getParent()->getId() . '\'');
                        }
                    }

                    $colSpanCounter = $tempColumnDefinitions[$columnCount]->getColSpan() == 0 ? 0 : $tempColumnDefinitions[$columnCount]->getColSpan() - 1;
                }

                $i = 0;
                //save changes
                foreach ($newColumnDefinitions as $column) {
                    $column->setOrder($i);
                    $item->setColumnItem($column->getDisplayId(), $column);
                    $i++;
                }

                //reorder column via column->order for create in good order in the factory
                $item->reOrderColumns();
            }
        }
    }

    public function getObjectFactory()
    {
        return $this->factory;
    }

    public function setObjectFactory($factory)
    {
        $this->factory = $factory;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    
    public function setModuleType($type)
    {
        $this->moduleType = $type;    
    }
    
    public function getModuleType()
    {
        return $this->moduleType;
    }

}
