<?php

namespace Fuller\ReportBundle\Definition\Table;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Fuller\ReportBundle\Definition\Table\HeadDefinition;
use Fuller\ReportBundle\Definition\Table\GroupDefinition;
use Fuller\ReportBundle\Definition\Table\ColumnDefinition;
use Fuller\ReportBundle\Definition\ReportDefinition;
use Fuller\ReportBundle\Definition\ReportDefinitionInterface;

/**
 * Fuller\ReportBundle\Definition\Table\TableDefinition
 *
 */
class TableDefinition extends Definition implements ReportDefinitionInterface
{

    protected $id;
    protected $headDefinition;
    protected $bodyDefinition;
    protected $factoryService;

    public function __construct(array $exportConfigs, $id = 'table')
    {
        parent::__construct($exportConfigs);
        $this->id = $id;
        $this->setFactoryService("report.table.factory");
        $this->initHeadDefinition($exportConfigs);
        $this->initBodyDefinition($exportConfigs);
    }

    protected function initHeadDefinition(array $exportConfigs)
    {
        $this->headDefinition = new headDefinition($exportConfigs);
        $this->headDefinition->setParent($this);

        return $this->headDefinition;
    }

    protected function initBodyDefinition(array $exportConfigs)
    {
        $this->bodyDefinition = new GroupDefinition('body', $exportConfigs);
        $this->bodyDefinition->setParent($this);

        return $this->bodyDefinition;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHeadDefinition()
    {
        return $this->headDefinition;
    }

    public function getBodyDefinition()
    {
        return $this->bodyDefinition;
    }

    public function setParent($parent)
    {
        if (isset($parent) && !$parent instanceof ReportDefinition) {
            throw new UnexpectedTypeException($this->parent, 'Fuller\ReportBundle\Definition\ReportDefinition');
        }
        parent::setParent($parent);
    }

    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }
        return $this->path = '\\' . $this->excludeSpecialCharacter($this->id);
    }

    public function setFactoryService($serviceName)
    {
        $this->factoryService = $serviceName;

        return $this;
    }

    public function getFactoryService()
    {
        return $this->factoryService;
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
                //echo "<br>groupDef : ".$item->getId();
                $this->subGroupDefinition($item);
            }

            //compare head to rowDefinition, add column missing, sort new items
            if ($item instanceof RowDefinition) {
                //echo "<br>rowDef";
                $headColumnDefinitions = $this->headDefinition->getColumns();
                ksort($headColumnDefinitions);

                $itemColumnDefinitions = $item->getColumns();

                //echo '<br>row ';
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

}
