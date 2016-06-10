<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Simplifier;

use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\Row;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Simplifier\DataReportSimplifier.
 */
class DataReportSimplifier
{
    protected $table;

    /**
     * @var array Rows will be displayed as Headers
     */
    protected $groupHeadingRows = array();

    /**
     * @var array Level count of recursive headers
     */
    protected $groupHeadingLevel;

    /**
     * @var array rows onwed by group, the ids of headers will be added to the data
     */
    protected $rows = array();

    /**
     * @var array list of column name
     */
    protected $columnList;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function getSimpleTable()
    {
        return $this->getTableArray($this->table);
    }

    /**
     * Get an array.
     *
     * @return array
     */
    protected function getTableArray($table)
    {
        $data = array();

        //Table Head
        $data['head'] = $this->getHeadArray($table->getHead());

        $data['nbColumns'] = count($data['head']);

        //body
        $this->getGroupArray($table->getBody());
        $data['bodyRows'] = $this->rows;

        //groupsHeading
        $data['groupHeadingLevel'] = $this->groupHeadingLevel;
        $data['groupHeadingRows'] = $this->groupHeadingRows;

        return $data;
    }

    protected function getHeadArray($head)
    {
        $this->columnList = array_keys($head->getColumns());

        foreach ($head->getColumns() as $displayId => $column) {
            $data[$displayId] = $column['label'];
        }

        return $data;
    }

    protected function getGroupArray($group, $groupHeading = array(), $groupHeadingId = 0)
    {
        foreach ($group->getItems() as $item) {
            if ($item instanceof Row) {
                //means it is a group Head
                if ($item->getDefinition()->getOption('unique') === true) {
                    $this->groupHeadingRows[] = $this->getRowArray($item);
                    $groupHeadingId = count($this->groupHeadingRows) - 1;
                    $groupHeading[] = $groupHeadingId;
                } else {
                    $this->rows[] = $this->getRowArray($item, $groupHeading);
                }
            }
        }

        foreach ($group->getItems() as $item) {
            if ($item instanceof Group) {
                $this->getGroupArray($item, $groupHeading, $groupHeadingId);
                ++$groupHeadingId;
            }
        }
    }

    protected function getRowArray($row, $groupHeading = array())
    {
        if (!$this->groupHeadingLevel && count($groupHeading) > 0) {
            $this->groupHeadingLevel = count($groupHeading);
        }

        $arrayRow = array();
        foreach ($this->columnList as $key => $displayId) {
            if (isset($row->getColumns()[$displayId]) && $row->getColumns()[$displayId]->getDefinition()->getType() != ColumnDefinition::TYPE_DATA) {
                $arrayRow[] = $row->getColumns()[$displayId]->getData();
            } else {
                $arrayRow[] = null;
            }
        }

        foreach ($groupHeading as $groupHeadingId) {
            $arrayRow[] = $groupHeadingId;
        }

        return $arrayRow;
    }
}
