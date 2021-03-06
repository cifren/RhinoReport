<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Simplifier;

use Earls\RhinoReportBundle\Module\Table\TableObject\Table;
use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\Row;
use Earls\RhinoReportBundle\Module\Table\TableObject\Column;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;
use Earls\RhinoReportBundle\Templating\Excel\Style\Style;
use Earls\RhinoReportBundle\Templating\Excel\Translator\CssXmlStyleTranslator;
use Earls\RhinoReportBundle\Module\Table\Templating\SystemTemplate\Util\XlsApplyFormula;
use Earls\RhinoReportBundle\Module\Table\Factory\TableFactory;
use Earls\RhinoReportBundle\Module\Table\Util\xlsApplyConditionalFormatting;

/**
 *  Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Simplifier\XlsReportSimplifier.
 *
 *  Transform ReportObject in Array and Convert all formatExcel for example '=sum(A1:A5)+sum(B1:B5)'
 */
class XlsReportSimplifier
{
    protected $table;
    protected $pageBreaks;
    protected $style;
    protected $lastRowPosition;
    protected $factoryTable;
    protected $xlsApplyFormula;
    protected $defaultStyle;
    protected $currentEvenOddRowSpanByGroupParent;

    public function __construct(XlsApplyFormula $xlsApplyFormula, $defaultStyle = true, TableFactory $factoryTable = null)
    {
        $this->xlsApplyFormula = $xlsApplyFormula;
        $this->factoryTable = $factoryTable;
        $this->defaultStyle = $defaultStyle;
        $this->xlsApplyConditionalFormatting = new xlsApplyConditionalFormatting();
    }

    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    public function getSimpleTable()
    {
        if (!isset($this->table)) {
            throw new \Exception('You must set a table');
        }

        return $this->getTableXmlArray($this->table);
    }

    /**
     * Array with html class from groups and Data.
     *
     * @return array $arrayHtml
     */
    protected function getTableXmlArray(Table $table)
    {
        $xmlArray = array();
        $data = array();

        //default style
        if ($this->defaultStyle) {
            $defaultActiveStyle = array('border-style' => 'solid', 'border-width' => 'thin');
            $defaultHeaderStyle = array('font-weight' => 'bold', 'text-align' => 'center');
        } else {
            $defaultActiveStyle = array();
            $defaultHeaderStyle = array();
        }
        if ($table->getDefinition()->getExportConfig('Excel')) {

            //create bloc for report information
            $rptInfo = $table->getDefinition()->getExportConfig('Excel')->getRptInfo();
            if (isset($rptInfo)) {
                $tableObject = $this->factoryTable->setDefinition($rptInfo)->build()->getItem();

                $simplifier = new self($this->xlsApplyFormula, false);
                $xmlArray['rpt_info']['body'] = $simplifier->setTable($tableObject)->getSimpleTable()['body'];
                //shift for formula / +1 because of space after report information
                $shift = count($xmlArray['rpt_info']['body']);
            }

            $this->tableOddEven = $table->getDefinition()->getExportConfig('Excel')->getTableOddEven();
            $this->style = $table->getDefinition()->getExportConfig('Excel')->getStyleTable();
            $this->style = \Earls\RhinoReportBundle\Templating\Excel\Style\StyleUtility::parseStyle($this->style);

            //style by default for all columns
            if (!isset($this->style['default-active'])) {
                $this->style['default-active'] = $defaultActiveStyle;
            }

            //style by default for header
            if (!isset($this->style['header'])) {
                $this->style['header'] = $defaultHeaderStyle;
            }
            $this->style['header'] = $this->style['header'] + $this->style['default-active'];

            //convert every column name by column index
            $columnConfigWithIndex = array();
            $columnConfig = $table->getDefinition()->getExportConfig('Excel')->getColumn();
            $i = 1;
            foreach (array_keys($table->getHead()->getColumns()) as $displayId) {
                if (isset($columnConfig[$displayId])) {
                    $columnConfigWithIndex[$i] = $columnConfig[$displayId];
                }
                ++$i;
            }

            if (count($columnConfigWithIndex) != count($columnConfig)) {
                $keyConfig = array_keys($columnConfig);
                foreach (array_keys($table->getHead()->getColumns()) as $key) {
                    if (isset($keyConfig[$key])) {
                        unset($keyConfig[$key]);
                    }
                }
                throw new \Exception('Column(s) \''.implode(', ', $keyConfig).'\' from \'column\' in excel configuration don\'t exist, available choices are \''.implode(', ', array_keys($table->getHead()->getColumns())).'\'');
            }
            //valid and add column options
            $columnTransformer = new \Earls\RhinoReportBundle\Templating\Excel\Transformer\ColumnConfigTransformer($columnConfigWithIndex);
            $xmlArray['columnConfig'] = $columnTransformer->transform();

            //Valid and add print options
            $printTransformer = new \Earls\RhinoReportBundle\Templating\Excel\Transformer\PrintConfigTransformer($table->getDefinition()->getExportConfig('Excel')->getPrint());
            $xmlArray['printConfig'] = $printTransformer->transform();

            //Valid and add print options
            $xmlArray['protectionConfig'] = $table->getDefinition()->getExportConfig('Excel')->getProtection();
        } else {
            $this->style['default-active'] = $defaultActiveStyle;
            $this->style['header'] = $defaultHeaderStyle + $defaultActiveStyle;
        }

        $this->xlsApplyFormula->setTable($table);
        $this->xlsApplyFormula->applyPositionAndFormula();

        $this->xlsApplyConditionalFormatting->setTable($table);
        $xmlArray['conditionalFormatting'] = $this->xlsApplyConditionalFormatting->getObjectBloc();

        //Table Head
        foreach ($table->getHead()->getColumns() as $displayId => $column) {
            $data[$displayId] = array(
                'attr' => array('id' => 'column_'.$displayId) + $column['attr'],
                'data' => strip_tags($column['label']),
            );
        }
        $printTitleRange = null;
        //if print config exist
        if ($table->getDefinition()->getExportConfig('Excel')) {
            $print = $table->getDefinition()->getExportConfig('Excel')->getPrint();
            //print title start after print informations
            if (isset($print) && !empty($print) && isset($print['print_titles'])) {
                $baseShift = 2; //where header start
                if (isset($shift)) {
                    $shift += $baseShift;
                } else {
                    $shift = $baseShift;
                }
                $printTitleRange = array('top' => $shift, 'bottom' => $shift);
            }
        }

        $attr = $table->getHead()->getAttributes();
        $attr['class'][] = 'group_head';

        //convert array to string
        if (isset($attr['class'])) {
            $attr['class'] = implode(' ', $attr['class']);
        }

        $xmlArray['head'] = array(
            'attr' => $attr,
            'columns' => $data, );

        //body
        $arrayBody = $this->getRowsXmlArrayFromGroup($table->getBody());
        $this->compileStyle($arrayBody);

        $xmlArray['body'] = $arrayBody;

        if ($this->style) {
            $translator = new CssXmlStyleTranslator();
            $style = $translator->translate($this->style);
        } else {
            $style = new Style();
        }

        //add pagebreaks
        $xmlArray['pagebreaks'] = $this->pageBreaks;

        //add translated style
        $xmlArray['style'] = $style;

        //add printTitleRange
        $xmlArray['printTitleRange'] = $printTitleRange;

        return $xmlArray;
    }

    protected function getRowsXmlArrayFromGroup(Group $group, $class = array())
    {
        $xmlArray = array();

        //class
        $class[] = 'group_'.$group->getGenericId();

        foreach ($group->getItems() as $item) {
            if ($item instanceof Row) {
                //if no column in $xmlArrayColumn => row are all columnData
                $xmlArrayColumn = $this->getRowsXmlArray($item);
                if (!empty($xmlArrayColumn)) {
                    $attr = $item->getAttributes();
                    if (!isset($attr['class'])) {
                        $allClasses = $class;
                    } else {
                        $allClasses = array_merge($class, $attr['class']);
                    }
                    if ($item->getDefinition()->getExportConfig('Excel')) {
                        $attrExcel = $item->getDefinition()->getExportConfig('Excel')->getAttr();
                        if (isset($attrExcel)) {
                            $attr = array_merge($attr, $attrExcel);
                        }
                    }
                    $attr['class'] = implode(' ', array_intersect(array_keys($this->style), $allClasses));

                    $xmlArray[] = array(
                        'attr' => $attr,
                        'columns' => $xmlArrayColumn,
                    );
                }
            }

            if ($item instanceof Group) {
                //count for rowspan
                $i = 0;
                foreach ($this->getRowsXmlArrayFromGroup($item, $class) as $row) {
                    if ($item->getRowspans()) {
                        //do nothing for first line
                        if ($i == 0) {
                            //index first row from group
                            $indexFirstRow = count($xmlArray);
                        } else {
                            foreach ($row['columns'] as $displayId => $column) {
                                if (in_array($displayId, $item->getRowspans())) {
                                    unset($row['columns'][$displayId]);
                                }
                            }
                        }
                        ++$i;
                    }

                    $xmlArray[] = $row;
                }

                if ($item->getDefinition()->getExportConfig('Excel') && $item->getDefinition()->getExportConfig('Excel')->isPageBreak()) {
                    $this->pageBreaks[] = $this->lastRowPosition;
                }

                if ($item->getRowspans()) {
                    $xmlArray[$indexFirstRow] = $this->applyRowSpanOnFirstRow($item, $xmlArray[$indexFirstRow], $i);
                    $xmlArray[$indexFirstRow] = $this->applyEvenOddOnRowSpan($group, $item, $xmlArray[$indexFirstRow]);
                }
            }
        }

        return $xmlArray;
    }

    protected function applyRowSpanOnFirstRow(Group $item, array $row, $nbRow)
    {
        //add attribute rowspan for first row
        //for all column add attribute rowspan
        foreach ($row['columns'] as $displayId => $column) {
            $row['columns'][$displayId]['attr']['class'][] = 'rowspan-head';
            if (in_array($displayId, $item->getRowspans())) {
                $row['columns'][$displayId]['attr']['rowspan'] = $nbRow;
            }
        }

        return $row;
    }

    protected function applyEvenOddOnRowSpan(Group $parent, Group $item, array $row)
    {
        $parentId = $parent->getParentPath();
        if (isset($this->currentEvenOddRowSpan[$parentId])) {
            //switch class
            $this->currentEvenOddRowSpan[$parentId] = $this->currentEvenOddRowSpan[$parentId] == 'even' ? 'odd' : 'even';
        } else {
            //init current
            $this->currentEvenOddRowSpan[$parentId] = 'even';
        }

        if (isset($this->tableOddEven['row']) && $this->tableOddEven['row']['active'] == true) {
            //for all column add new class even/odd
            foreach ($row['columns'] as $displayId => $column) {
                if (in_array($displayId, $item->getRowspans())) {
                    //remove existing class even/odd
                    $row['columns'][$displayId]['attr']['class'] = $this->removeEvenOddClassFromColumn($row['columns'][$displayId]['attr']['class']);
                    //apply the new class
                    $row['columns'][$displayId]['attr']['class'][] = $this->tableOddEven['row']['classes'][$this->currentEvenOddRowSpan[$parentId]];
                }
            }
        }

        return $row;
    }

    protected function removeEvenOddClassFromColumn(array $classes)
    {
        $arrayEvenOddClasses = array($this->tableOddEven['row']['classes']['even'], $this->tableOddEven['row']['classes']['odd']);

        return array_diff($classes, $arrayEvenOddClasses);
    }

    protected function getRowsXmlArray(Row $row)
    {
        //if position exist means it is a row not containing only columnData
        if ($row->getPosition()) {
            $this->lastRowPosition = $row->getPosition();
        }

        $inheritedAttr['class'] = array();
        //oddEven class row
        if (isset($this->tableOddEven['row']) && $this->tableOddEven['row']['active'] == true) {
            if ($row->getPosition() % 2 == 0) {
                $inheritedAttr['class'][] = $this->tableOddEven['row']['classes']['even'];
            } else {
                $inheritedAttr['class'][] = $this->tableOddEven['row']['classes']['odd'];
            }
        }

        $xmlArray = array();
        foreach ($row->getColumns() as $displayId => $column) {
            if ($column->getDefinition()->getType() != ColumnDefinition::TYPE_DATA) {
                $xmlArray[$displayId] = $this->getColumnArray($column, $inheritedAttr);
                $xmlArray[$displayId]['columnPosition'] = $column->getPosition();
            }
        }

        return $xmlArray;
    }

    /**
     * Create column xml.
     *
     * Priority for attr for inheritance (parent to own)
     *      - Default-style (style of table)
     *      - Attr from general config
     *      - Attr from export config
     *
     * @param \Earls\RhinoReportBundle\Module\Table\TableObject\Column $column
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getColumnArray(Column $column, array $attr)
    {
        $attr['class'] = $attr['class'] ? $attr['class'] : array();

        //oddEven class column
        if (isset($this->tableOddEven['column']) && $this->tableOddEven['column']['active'] == true) {
            if ($column->getPosition() % 2 == 0) {
                $attr['class'][] = $this->tableOddEven['column']['classes']['even'];
            } else {
                $attr['class'][] = $this->tableOddEven['column']['classes']['odd'];
            }
        }

        //default style for all column
        if ($this->defaultStyle) {
            //will add default-active at the first position, can be overwrite
            $attr['class'][] = 'default-active';
        }

        //general config
        $attr = array_merge_recursive($attr, $column->getAttributes());

        if (isset($attr['class']) && !is_array($attr['class'])) {
            throw new \Exception('Attribute `class´ for column `'.$column->getDefinition()->getPath().'´ should be an array');
        }

        //get classes from parent via special field `classForColumns´
        if ($column->getParent()->getDefinition()->getExportConfig('excel')) {
            $attrParent = $column->getParent()->getDefinition()->getExportConfig('excel')->getAttr();
            if (isset($attrParent['classForColumns'])) {
                if (!is_array($attrParent['classForColumns'])) {
                    throw new \Exception('Attribute `classForColumns´ for column `'.$column->getParent()->getDefinition()->getPath().'´ should be an array');
                }
                $attr['class'] = array_merge($attr['class'], $attrParent['classForColumns']);
            }
        }

        //merge common config with export config
        if ($column->getDefinition()->getExportConfig('excel')) {
            $exportAttr = $column->getDefinition()->getExportConfig('excel')->getAttr();
            if (isset($exportAttr['class']) && !is_array($exportAttr['class'])) {
                throw new \Exception('Attribute `classForColumns´ for column `'.$column->getParent()->getDefinition()->getPath().'´ should be an array');
            }
            if (isset($exportAttr['class'])) {
                $attr['class'] = array_merge_recursive($attr['class'], $exportAttr['class']);
            }
            //merge excel type
            if (isset($exportAttr['type'])) {
                $attr['type'] = $exportAttr['type'];
            }
        }
        if (isset($attr['type']) && $attr['type'] == 'Number') {
            $column->setData((float) (preg_replace("/[^-0-9\.]/", '', $this->getColumnValue($column))));
            //make sure first letter is uppercase for excel
            $attr['type'] = ucfirst($attr['type']);
        }

        //automatically determine if number or string type for excel
        if (!isset($attr['type']) && is_numeric($this->getColumnValue($column))) {
            $attr['type'] = 'Number';
        }

        //merge common config with export config for style
        if ($column->getDefinition()->getExportConfig('excel')) {
            $exportAttr = $column->getDefinition()->getExportConfig('excel')->getAttr();
            if (isset($attr['style']) && isset($exportAttr['style'])) {
                $attr['style'] = array_merge($attr['style'], $exportAttr['style']);
            }
        }

        $xmlArray = array(
            'uniqueId' => $column->getDefinition()->getPath().$column->getPosition().$column->getRow()->getPosition(),
            'attr' => $attr,
            'colspan' => null,
            'data' => $this->xmlReplaceIllegalCharacter($this->getColumnValue($column)),
            'formula' => $this->xmlReplaceIllegalCharacter($column->getFormula()),
        );

        return $xmlArray;
    }

    protected function getColumnValue(Column $column)
    {
        return $column->getBaseValue() !== null ? $column->getBaseValue() : $column->getData();
    }

    private function xmlReplaceIllegalCharacter($data)
    {
        $data = preg_replace('/[^(\x20-\x7F)]*/', '', $data);

        return str_replace(array('<', '>', '&', "'", '"'), array(htmlspecialchars('<'), htmlspecialchars('>'), htmlspecialchars('&'), htmlspecialchars("'"), htmlspecialchars('"')), $data);
    }

    private function compileStyle(&$xmlArray)
    {
        foreach ($xmlArray as $keyRow => $row) {
            foreach ($row['columns'] as $keyColumn => $column) {
                $attr = $column['attr'];
                /*  merge all classes into one class, name will be class1~class2,
                 *  inheritance =>
                 *    default-style -> general classes -> parent classes -> export classes -> style
                 */
                if (isset($attr['class'])) {
                    //will keep only classes declared in excel export config style from body
                    $attr['class'] = array_intersect($attr['class'], array_keys($this->style));
                    //create a new class, merge between all classes
                    if (!empty($attr['class'])) {
                        $nameClass = implode('~', $attr['class']);
                        //create dynamically style from all classes
                        if (!isset($this->style[$nameClass])) {
                            $newClass = array();
                            foreach ($attr['class'] as $name) {
                                foreach ($this->style[$name] as $key => $rule) {
                                    $newClass[$key] = $rule;
                                }
                            }
                            $this->style[$nameClass] = $newClass;
                        }
                        $attr['class'] = $nameClass;
                    } else {
                        $attr['class'] = null;
                    }
                }

                if (isset($attr['style'])) {
                    $styleCustomClass = $xmlArray[$keyRow]['columns'][$keyColumn]['uniqueId'];
                    //check if custom style already exist for this column, and create custom for this column
                    if (!isset($this->style[$styleCustomClass])) {
                        if (!is_array($attr['style'])) {
                            throw new \Exception('Attribute `style´ for column `'.$column->getDefinition()->getPath().'´ should be an array');
                        }
                        //add parent inherit to class -> will be translated after
                        if ($attr['class']) {
                            $attr['style']['parent'] = $attr['class'];
                        }
                        //create custom class for this column
                        $this->style[$styleCustomClass] = $attr['style'];
                    }
                    $attr['class'] = $styleCustomClass;
                }

                $xmlArray[$keyRow]['columns'][$keyColumn]['attr'] = $attr;
            }
        }
    }
}
