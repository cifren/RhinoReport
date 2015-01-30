<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\SystemTemplate\Util;

use Earls\RhinoReportBundle\Module\Table\TableObject\Table;
use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\Row;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Templating\SystemTemplate\Util\XlsApplyFormula
 */
class XlsApplyFormula
{

    protected $table;
    protected $TableRetrieverHelper;
    protected $rowShift;
    protected $listFormula = array();

    public function __construct()
    {
        $this->TableRetrieverHelper = new \Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper;
        $this->rowShift = 3; //+2 because of the empty line + header
    }

    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

//    public function addRowShift($shift)
//    {
//        $this->setRowShift($this->rowShift + 1);
//
//        return $this;
//    }

    protected function setRowShift($shift)
    {
        $this->rowShift = $shift;

        return $this;
    }

    public function applyPositionAndFormula()
    {
        //add position number for each row with DefinitionType Display and a position letter foreach column with DefinitionType Display
        $this->applyPositionGroup($this->table->getBody(), $this->rowShift);

        $this->applyFormula($this->table);
    }

    protected function applyPositionGroup(Group $group, &$rowPosition)
    {
        foreach ($group->getItems() as $item) {
            if ($item instanceof Group) {
                $this->applyPositionGroup($item, $rowPosition);
            }

            if ($item instanceof Row) {
                $this->applyPositionRowAndColumn($item, $rowPosition);
            }
        }
    }

    protected function applyPositionRowAndColumn(Row $row, &$rowPosition)
    {
        //only for DefinitionType Display
        if ($row->hasTypeDefinition(ColumnDefinition::TYPE_DISPLAY)) {
            $columnPosition = 1;
            $previousCol = null;
            foreach ($row->getColumns() as $column) {
                //list all formulas found to use it after
                if ($column->getDefinition()->getType() == ColumnDefinition::TYPE_DISPLAY) {
                    if ($column->getDefinition()->getFormulaExcel()) {
                        $this->listFormula[] = $column->getFullPath();
                    }
                    if ($previousCol) {
                        for ($i = 0; $i <= $previousCol->getAttribute('colspan') - 2; $i++) {
                            $columnPosition++;
                        }
                        $column->setPosition($columnPosition);
                    } else {
                        $column->setPosition($columnPosition);
                    }
                    ++$columnPosition;
                    $previousCol = $column;
                }
            }
            $row->setPosition($rowPosition);
            ++$rowPosition;
        }
    }

    protected function applyFormula(Table $table)
    {
        $this->TableRetrieverHelper->setTable($table);

        //Basic stuff without class managing this like action or actionGroup, maybe some day
        //apply all formula
        foreach ($this->listFormula as $columnPath) {
            $columnBase = $this->TableRetrieverHelper->getItemFromDataPath($columnPath);

            $definitionFormula = $columnBase->getDefinition()->getFormulaExcel();
            $argColumn = array();

            foreach ($definitionFormula['columns'] as $columnFormulaPath) {

                //get column from a designated group
                if (is_array($columnFormulaPath)) {
                    $fromItem = $this->TableRetrieverHelper->getParentOrSubItemsFromGenericPath($columnFormulaPath['fromGroup'], $columnBase->getRow()->getGroup());
                    $columnFormulaPath = $columnFormulaPath['column'];
                } else { //from default group
                    $fromItem = $columnBase->getRow()->getGroup();
                }

                //get column/columns
                $aryColumn = $this->TableRetrieverHelper->getParentOrSubItemsFromGenericPath($columnFormulaPath, $fromItem);

                // Exception: assertion if $aryColumn is empty or doesn't have index 0
                if (empty($aryColumn)) {
                    throw new \Exception('Array `$aryColumn´ shouldn\'t be null (in path:"'.$columnFormulaPath. '").');
                } elseif (empty($aryColumn[0])) {
                    throw new \Exception('Array `$aryColumn´ should have an element at index 0 (in path:"'.$columnFormulaPath. '").');
                }

                if (count($aryColumn) > 1 && $aryColumn[0]->getDefinition()->getType() == ColumnDefinition::TYPE_DATA) {
                    throw new \Exception('Column Data `' . $aryColumn[0]->getDefinition()->getPath() . '´ can\'t work through groups');
                } elseif ($aryColumn[0]->getDefinition()->getType() == ColumnDefinition::TYPE_DATA) {
                    $argColumn[] = $aryColumn[0]->getData();
                    continue;
                }

                //save time, directly get position
                if (count($aryColumn) == 1) {
                    $argColumn[] = $aryColumn[0]->getFullPosition();
                    continue;
                } elseif (count($aryColumn) == 0) {
                    continue;
                }

                $cell = array();
                foreach ($aryColumn as $itemColumn) {
                    $cell[$itemColumn->getRow()->getPosition()] = $itemColumn;
                }

                //array looks like
                //$cell['1']
                //$cell['3']
                //$cell['2']
                //sort by horizontal position, means 1, 2, 3, 4...
                ksort($cell);

                $lastPosition = null;

                //look if row follow next one, 1, 2, 3, 4, 5 => sum(A1:A5) - otherwise 1,3,5,8,=> A1+A3+A5+A8
                $columnFormula = array();
                $count = 1;
                foreach ($cell as $position => $column) {
                    //if first loop
                    if ($lastPosition == null) {
                        $firstColumn = $lastColumn = $column;
                    } elseif ($position == $lastPosition + 1) { //if next one follow previous one, 1 -> 2 -> 3
                        $lastColumn = $column;
                    }
                    if (($position != $lastPosition + 1 || count($cell) == $count) && $lastPosition != null) { //if not following or if last item -> save
                        if ($firstColumn == $lastColumn) {
                            $columnFormula[] = $firstColumn->getFullPosition();
                            //if last, save
                            if (count($cell) == $count) {
                                $columnFormula[] = $column->getFullPosition();
                            }
                        } else {
                            $columnFormula[] = $firstColumn->getFullPosition() . ':' . $lastColumn->getFullPosition();
                        }
                        $firstColumn = $lastColumn = $column;
                    }

                    $count++;
                    $lastPosition = $position;
                }

                $argColumn[] = implode('+', $columnFormula);
            }
            $formula = '=' . vsprintf($definitionFormula['formula'], $argColumn);
            $columnBase->setFormula($formula);
            $columnBase->setData(null);
            $columnBase->setBaseValue(null);
        }
    }

}
