<?php

namespace Earls\RhinoReportBundle\Module\Table\Util;

use Earls\RhinoReportBundle\Module\Table\TableObject\Table;
use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper;
use Earls\RhinoReportBundle\Module\Table\Util\xlsConditionalFormattingBloc;
use Earls\RhinoReportBundle\Templating\Excel\Translator\CssXmlStyleConditionalFormattingTranslator;

/**
 * Earls\RhinoReportBundle\Module\Table\Util\xlsApplyConditionalFormatting
 */
class xlsApplyConditionalFormatting
{

    protected $table;

    /**
     *
     * @var TableRetrieverHelper 
     */
    protected $tableRetrieverHelper;

    /**
     *
     * @var CssXmlStyleConditionalFormattingTranslator 
     */
    protected $styleTranslator;
    protected $rowShift;
    protected $listFormula = array();

    public function __construct()
    {
        $this->tableRetrieverHelper = new TableRetrieverHelper();
        $this->rowShift = 3; //+2 because of the empty line + header
        $this->styleTranslator = new CssXmlStyleConditionalFormattingTranslator();
    }

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    protected function setRowShift($shift)
    {
        $this->rowShift = $shift;

        return $this;
    }

    public function getObjectBloc()
    {
        $blocList = $this->getListOnGroup($this->table->getBody());
        return $blocList;
    }

    protected function getListOnGroup(Group $group)
    {
        $list = array();
        if (count($group->getDefinition()->getConditionalFormattings()) > 0) {
            $list = $this->getCondFormatBloc($group);
        }

        foreach ($group->getItems() as $item) {
            if ($item instanceof Group) {

                $list = array_merge($this->getListOnGroup($item), $list);
            }
        }

        return $list;
    }

    protected function getCondFormatBloc(Group $item)
    {
        $blocList = array();
        foreach ($item->getDefinition()->getConditionalFormattings() as $condFormat) {
            $columns = $this->tableRetrieverHelper->getColumns($condFormat['selectedColumn'], $item);
            if (count($columns) <= 0) {
                continue;
            }
            if (count($columns) > 1) {
                $maxCol = count($columns) - 1;
                $range = $columns[0]->getFullPosition() . ":" . $columns[$maxCol]->getFullPosition();
            } else {
                $range = $columns[0]->getFullPosition();
            }
            //condition
            $row = $columns[0]->getParent();
            foreach ($condFormat['displayIds'] as $displayId) {
                $args[] = 'RC' . $row->getColumn($displayId)->getPosition();
            }
            $condition = vsprintf($condFormat['condition'], $args);

            $blocList[] = $this->getNewBloc($range, $condition, $condFormat['classes']);
        }

        return $blocList;
    }

    protected function getNewBloc($range, $condition, $classes)
    {
        $existingClasses = $this->getExistingStyle($classes);
        $style = $this->getStyleFromClasses($existingClasses);

        return new xlsConditionalFormattingBloc($range, $condition, $style);
    }

    protected function getExistingStyle(array $classes)
    {
        $existingClasses = array();
        $tableStyle = $this->getTableExportStyle();

        foreach ($classes as $class) {
            if (isset($tableStyle[$class])) {
                $existingClasses[] = $tableStyle[$class];
            }
        }

        return $existingClasses;
    }

    protected function getStyleFromClasses(array $classes)
    {
        $translatedClasses = $this->styleTranslator->translate($classes);

        $compiledClasses = array();

        //compile style
        foreach ($translatedClasses as $class) {
            $compiledClasses = array_merge_recursive($compiledClasses, $class);
        }

        $style = null;
        foreach ($compiledClasses as $rule => $value) {
            $style .= $rule . ':' . $value . ';';
        }

        return $style;
    }

    protected function getTableExportStyle()
    {
        return $this->table->getDefinition()->getExportConfig('Excel')->getStyleTable();
    }

}
