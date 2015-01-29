<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Simplifier;

use Earls\RhinoReportBundle\Module\Table\TableObject\Table;
use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\Row;
use Earls\RhinoReportBundle\Module\Table\TableObject\Column;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;

class DataReportSimplifier
{

    protected $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function getSimpleTable()
    {
        return $this->getTableArray($this->table, true, true, true, true);
    }

    /**
     * Get an array
     *
     * @param boolean $fullColumn             Fill up column array if column doesn't exist in object
     * @param boolean $mergeColumnName        Remove first line column name and change column id for next line with first row values
     * @param boolean $orderByColumn          Order by columnHead all rows, if $removeNoExistingColumn = false, put all not existing columnHead at the end of the row
     * @param boolean $removeNoExistingColumn Remove all column not existing in columnHead values
     *
     * @return array
     */
    protected function getTableArray($table, $fullColumn = false, $mergeColumnName = false, $orderByColumn = false, $removeNoExistingColumn = false)
    {

        $arrayIterator = array();

        //Table Head
        $arrayIterator[] = $this->getHeadArray($table->getHead());

        //body
        $arrayIterator = array_merge($arrayIterator, $this->getGroupArray($table->getBody()));

        //add missing column in each row and/or replace row column id by columnHead array values
        if ($fullColumn || $mergeColumnName || $orderByColumn || $removeNoExistingColumn) {
            $columnName = $arrayIterator[0];

            foreach ($arrayIterator as $id => $row) {
                //For columHead row
                if ($id == 0) {
                    continue;
                }

                if ($orderByColumn) {
                    $newRow = array();
                }

                //check foreach columnHead id if columnName match with row column Id
                foreach ($columnName as $key => $column) {
                    //add key if not existing
                    if ($fullColumn) {
                        if (!isset($row[$key])) {
                            $row[$key] = null;
                        }
                    }
                    //replace all id by columnHead values
                    if ($mergeColumnName) {
                        if (key_exists($key, $row)) {
                            $pos = array_search($key, array_keys($row));
                            $part1 = array_slice($row, 0, $pos + 1);
                            $part2 = array_slice($row, $pos + 1);
                            unset($part1[$key]);
                            $part1[$column] = $row[$key];
                            $row = array_merge($part1, $part2);
                        }
                        $newKey = $column;
                    } else {
                        $newKey = $key;
                    }

                    //Create new array of value order by ColumnHead order
                    if ($orderByColumn) {
                        if (key_exists($newKey, $row)) {
                            $newRow[$newKey] = $row[$newKey];
                            unset($row[$newKey]);
                        }
                    }
                }

                if (!$removeNoExistingColumn && $orderByColumn) { //add not existing column to existing
                    $row = array_merge($newRow, $row);
                } elseif ($removeNoExistingColumn && $orderByColumn) { //remove not existing column
                    $row = $newRow;
                } elseif ($removeNoExistingColumn && !$orderByColumn) { //remove not existing column
                    if (!$mergeColumnName) {
                        $row = array_intersect_key($row, $columnName);
                    } else {
                        $row = array_intersect_key($row, array_flip($columnName));
                    }
                }

                $arrayIterator[$id] = $row;
            }

            //remove firstLine if merge columnHead value
            if ($mergeColumnName) {
                array_splice($arrayIterator, 0, 1);
            }
        }

        return $arrayIterator;
    }

    protected function getHeadArray($head)
    {
        foreach ($head->getColumns() as $displayId => $column) {
            $arrayIterator[$displayId] = $column['label'];
        }

        return $arrayIterator;
    }

    protected function getGroupArray($group)
    {
        $arrayIterator = array();

        foreach ($group->getItems() as $item) {
            if ($item instanceof Row) {
                $column = $this->getRowArray($item);
                if(!empty($column))
                    $arrayIterator[] = $this->getRowArray($item);
            }

            if ($item instanceof Group) {
                $arrayIterator = array_merge($arrayIterator, $this->getGroupArray($item));
            }
        }

        return $arrayIterator;
    }

    protected function getRowArray($row)
    {
        $arrayRow = array();
        foreach ($row->getColumns() as $displayId => $column) {
            if ($column->getDefinition()->getType() != ColumnDefinition::TYPE_DATA) {
                $arrayRow[$displayId] = $column->getData();
            }
        }

        return $arrayRow;
    }
}
