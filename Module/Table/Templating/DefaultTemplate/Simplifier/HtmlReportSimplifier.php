<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Simplifier;

use Earls\RhinoReportBundle\Module\Table\TableObject\Table;
use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\Row;
use Earls\RhinoReportBundle\Module\Table\TableObject\Column;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;
use Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper;

/**
 * Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Simplifier\HtmlReportSimplifier.
 */
class HtmlReportSimplifier
{
    protected $table;

    /**
     * @var TableRetrieverHelper
     */
    protected $tableRetrieverHelper;

    public function __construct($table)
    {
        $this->table = $table;
        $this->tableRetrieverHelper = new TableRetrieverHelper();
    }

    public function getSimpleTable()
    {
        return $this->getTableHtmlArray($this->table);
    }

    /**
     * Array with html class from groups and Data.
     *
     * @return array $arrayHtml
     */
    protected function getTableHtmlArray(Table $table)
    {
        $htmlArray = array();

        //Table Head
        $data = array();
        if ($table->getHead()->getColumns()) {
            foreach ($table->getHead()->getColumns() as $displayId => $column) {
                //convert array to string
                if (isset($column['attr']['class'])) {
                    $column['attr']['class'] = implode(' ', $column['attr']['class']);
                }
                $data[$displayId] = array(
                    'attr' => array('id' => 'column_'.$displayId) + $column['attr'],
                    'data' => $column['label'],
                );
            }
        }

        $attr = $table->getHead()->getAttributes();
        $attr['class'][] = 'group_head';

        //convert array to string
        if (isset($attr['class'])) {
            $attr['class'] = implode(' ', $attr['class']);
        }

        $htmlArray['attr'] = $table->getAttributes();
        //convert array to string
        if (isset($htmlArray['attr']['class'])) {
            $htmlArray['attr']['class'] = implode(' ', $htmlArray['attr']['class']);
        }

        $htmlArray['head'] = array(
            'attr' => $attr,
            'columns' => $data, );

        $this->applyConditionalFormating($table);

        //body
        $arrayBody = $this->getGroupHtmlArray($table->getBody());

        $htmlArray['body'] = $arrayBody;

        return $htmlArray;
    }

    protected function getGroupHtmlArray(Group $group, $class = array())
    {
        $htmlArray = array();

        //class
        $class[] = 'group_'.$group->getGenericId();

        foreach ($group->getItems() as $item) {
            if ($item instanceof Row) {
                //if column inside row are all columnData
                $htmlArrayColumn = $this->getRowHtmlArray($item);

                if (!empty($htmlArrayColumn)) {
                    //merge common config with export config
                    if ($item->getDefinition()->getExportConfig('html')) {
                        $attr = array_merge_recursive($item->getDefinition()->getExportConfig('html')->getAttr(), $item->getAttributes());
                    } else {
                        $attr = $item->getAttributes();
                    }

                    //$attr['class'] = (isset($attr['class']) ? $attr['class'] . ' ' : '') . $class;
                    if (isset($attr['class'])) {
                        $attr['class'] = array_merge($attr['class'], $class);
                    } else {
                        $attr['class'] = $class;
                    }
                    $attr['style'] = (!isset($attr['style'])) ?: $this->getAttrStyle($attr['style']);

                    //convert array to string
                    $attr['class'] = implode(' ', $attr['class']);
                    $htmlArray[] = array(
                        'attr' => $attr,
                        'columns' => $this->getRowHtmlArray($item),
                    );
                }
            }
            if ($item instanceof Group) {
                //count for rowspan
                $i = 0;
                foreach ($this->getGroupHtmlArray($item, $class) as $row) {
                    if ($item->getRowspans()) {

                        //do nothing for first line
                        if ($i == 0) {
                            //index first row from group
                            $indexFirstRow = count($htmlArray);
                        } else {
                            foreach ($row['columns'] as $displayId => $column) {
                                if (in_array($displayId, $item->getRowspans())) {
                                    unset($row['columns'][$displayId]);
                                }
                            }
                        }
                        ++$i;
                    }

                    $htmlArray[] = $row;
                }

                //add attribute rowspan for first row
                if ($item->getRowspans()) {
                    $row = $htmlArray[$indexFirstRow];

                    //explode because class has been implode some step before
                    $row['attr']['class'] = explode(' ', $row['attr']['class']);
                    //rowspan-head appear only on rowspan
                    $row['attr']['class'][] = 'rowspan-head';

                    //convert array to string
                    $row['attr']['class'] = implode(' ', $row['attr']['class']);

                    //for all column add attribute rowspan
                    foreach ($row['columns'] as $displayId => $column) {
                        if (in_array($displayId, $item->getRowspans())) {
                            $row['columns'][$displayId]['attr']['rowspan'] = $i;
                        }
                    }

                    $htmlArray[$indexFirstRow] = $row;
                }
            }
        }

        return $htmlArray;
    }

    protected function getRowHtmlArray(Row $row)
    {
        $htmlArray = array();
        foreach ($row->getColumns() as $displayId => $column) {
            if ($column->getDefinition()->getType() != ColumnDefinition::TYPE_DATA) {
                $htmlArray[$displayId] = $this->getColumnHtmlArray($column);
            }
        }

        return $htmlArray;
    }

    protected function getColumnHtmlArray(Column $column)
    {
        $htmlArray = array();

        //merge common config with export config
        if ($column->getDefinition()->getExportConfig('html')) {
            $attr = array_merge_recursive($column->getDefinition()->getExportConfig('html')->getAttr(), $column->getAttributes());
        } else {
            $attr = $column->getAttributes();
        }

        $attr = array_map('unserialize', array_unique(array_map('serialize', $attr)));

        $attr['style'] = (!isset($attr['style'])) ?: $this->getAttrStyle($attr['style']);

        if (isset($attr['class'])) {
            //convert array to string
            $attr['class'] = implode(' ', $attr['class']);
        } else {
            $attr['class'] = null;
        }

        $htmlArray = array(
            'attr' => $attr,
            'colspan' => null,
            'data' => $column->getData(),
        );

        return $htmlArray;
    }

    /**
     * Convert array of style into string for html render.
     *
     * @param array $style
     *
     * @return string
     */
    protected function getAttrStyle(array $style)
    {
        $styleString[] = null;
        foreach ($style as $key => $value) {
            $styleString[] .= $key.':'.$value.';';
        }

        return implode('', $styleString);
    }

    protected function applyConditionalFormating(Table $table)
    {
        $body = $table->getBody();

        $this->applyConditionalFormatingOnGroup($body);
    }

    protected function applyConditionalFormatingOnGroup(Group $group)
    {
        if (count($group->getDefinition()->getConditionalFormattings()) > 0) {
            $this->applyConditionalFormatingOnColumns($group);
        }
        foreach ($group->getItems() as $item) {
            if ($item instanceof Group) {
                $this->applyConditionalFormatingOnGroup($item);
            }
        }
    }

    protected function applyConditionalFormatingOnColumns(Group $group)
    {
        foreach ($group->getItems() as $item) {
            if ($item instanceof Row) {
                $conditionalFormattings = $group->getDefinition()->getConditionalFormattings();
                foreach ($conditionalFormattings as $condFormat) {
                    $columns = $this->tableRetrieverHelper->getColumns($condFormat['selectedColumn'], $group);
                    foreach ($columns as $column) {
                        $row = $column->getParent();
                        $data = $this->fetchValues($row, $condFormat['displayIds']);
                        $evalStr = '$condition = '.vsprintf($condFormat['condition'], $data).';';
                        eval($evalStr);
                        if ($condition) {
                            $column = $row->getColumn($column->getId());
                            $column->setAttribute('class', (is_array($column->getAttribute('class')) ? array_merge($column->getAttribute('class'), $condFormat['classes']) : $condFormat['classes']));
                        }
                    }
                }
            }
        }
    }

    protected function fetchValues($row, $columnsName)
    {
        $aryValues = array();
        foreach ($columnsName as $columnName) {
            if ($row->getColumn($columnName) == null) {
                $aryValues[] = 0;
            } else {
                $data = $row->getColumn($columnName)->getNakedData();

                $aryValues[] = floatval($data);
            }
        }

        return $aryValues;
    }
}
