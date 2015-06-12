<?php

namespace Earls\RhinoReportBundle\Module\Table\Definition;

use Earls\RhinoReportBundle\Report\Definition\AbstractDefinitionBuilder;
use Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;
use Symfony\Component\DependencyInjection\Container;

/**
 * Earls\RhinoReportBundle\Module\Table\Definition\AdvancedTableDefinitionBuilder
 */
class AdvancedTableDefinitionBuilder extends AbstractDefinitionBuilder
{

    protected $parent;
    protected $availableExport;

    public function __construct($definitionClass)
    {
        parent::__construct($definitionClass);
        $this->availableExport = array('html' => $this->getHtmlExportConfigClass(), 'excel' => $this->getExcelExportConfigClass());
    }

    public function head()
    {
        if (!$this->getCurrentDefinition() instanceof TableDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function head()');
        }

        $this->setCurrentDefinition($this->getCurrentDefinition()->getHeadDefinition());

        return $this;
    }

    public function body()
    {
        if (!$this->getCurrentDefinition() instanceof TableDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function body()');
        }

        $this->setCurrentDefinition($this->getCurrentDefinition()->getBodyDefinition());

        return $this;
    }

    public function headColumns(array $columnNames)
    {
        if (!$this->getCurrentDefinition() instanceof HeadDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function headColumns()');
        }

        $this->setCurrentDefinition($this->getCurrentDefinition()->setColumns($columnNames));

        return $this;
    }

    public function group($id)
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function group()');
        }

        $this->setCurrentDefinition($this->getCurrentDefinition()->addGroup($id, $this->availableExport));

        return $this;
    }

    public function row()
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function row()');
        }

        $this->setCurrentDefinition($this->getCurrentDefinition()->addRow(array('unique' => false), $this->availableExport));

        return $this;
    }

    public function rowUnique()
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function rowUnique()');
        }

        $this->setCurrentDefinition($this->getCurrentDefinition()->addRow(array('unique' => true), $this->availableExport));

        return $this;
    }

    public function attr(array $attributes = array())
    {
        if (!$this->getCurrentDefinition() instanceof TableDefinition and ! $this->getCurrentDefinition() instanceof RowDefinition and ! $this->getCurrentDefinition() instanceof ColumnDefinition and ! $this->getCurrentDefinition() instanceof HeadDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function attr()');
        }
        if (isset($attributes['class']) && !is_array($attributes['class'])) {
            throw new \Exception('Attribute `class´ should be an array on element `' . $this->getCurrentDefinition()->getPath() . '´');
        }
        if (isset($attributes['style']) && !is_array($attributes['style'])) {
            throw new \Exception('Attribute `style´ should be an array on element `' . $this->getCurrentDefinition()->getPath() . '´');
        }
        $this->getCurrentDefinition()->setAttributes($attributes);

        return $this;
    }

    public function groupBy($id)
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function groupBy()');
        }
        if (!$this->getCurrentDefinition()->getId() == 'body') {
            throw new \Exception('groupBy is not allowed on body, you need to create a group');
        }

        $this->getCurrentDefinition()->setGroupBy($id);

        return $this;
    }

    /**
     * order data by columns
     *
     * @param  array or string                                                  $columns
     * @return \Earls\RhinoReportBundle\Module\Table\Definition\TableDefinitionBuilder
     * @throws \Exception
     */
    public function orderBy($columns)
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function groupBy()');
        }

        if (!is_array($columns)) {
            $columns = array($columns);
        }
        $this->getCurrentDefinition()->setOrderBy($columns);

        return $this;
    }

    public function column($displayId, $dataId = null)
    {
        $this->createColumn($displayId, ColumnDefinition::TYPE_DISPLAY, $dataId);

        return $this;
    }

    public function columnData($displayId, $dataId = null)
    {
        $this->createColumn($displayId, ColumnDefinition::TYPE_DATA, $dataId);

        return $this;
    }

    protected function createColumn($displayId, $type, $dataId)
    {
        if (!$this->getCurrentDefinition() instanceof RowDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function columnData()');
        }

        if (!$dataId) {
            //dont close columnDefinition
            $this->getCurrentDefinition()->setColumn($displayId, $type, $this->availableExport);
            $this->setCurrentDefinition($this->getCurrentDefinition()->getColumn($displayId));

            return $this;
        }

        //close ColumnDefinition
        $this->getCurrentDefinition()->setColumn($displayId, $type, $this->availableExport, $dataId);

        return $this;
    }

    //set data for column
    public function baseData($type, $arg)
    {
        if (!$this->getCurrentDefinition() instanceof ColumnDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function baseData()');
        }

        $this->getCurrentDefinition()->setBaseData($type, $arg);

        return $this;
    }

    //for excel purpose, freeze data
    public function baseValue(array $arg)
    {
        if (!$this->getCurrentDefinition() instanceof ColumnDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function baseValue()');
        }

        $this->getCurrentDefinition()->addAction('basevalue', $arg);

        return $this;
    }

    public function formulaExcel($formula, $columns)
    {
        if (!$this->getCurrentDefinition() instanceof ColumnDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function formulaExcel()');
        }


        if (!$this->getCurrentDefinition()->getType() == ColumnDefinition::TYPE_DATA) {
            throw new \Exception('Function "formatExcel" can\'t work on columnData');
        }

        $this->getCurrentDefinition()->setFormulaExcel(array('formula' => $formula, 'columns' => $columns));

        return $this;
    }

    public function action($name, array $arg)
    {
        if (!$this->getCurrentDefinition() instanceof ColumnDefinition && !$this->getCurrentDefinition() instanceof RowDefinition && !$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function columnAction()');
        }

        $this->getCurrentDefinition()->addAction($name, $arg);

        return $this;
    }

    public function groupAction($name, array $arg, $dependences = array())
    {
        if (!$this->getCurrentDefinition() instanceof ColumnDefinition && !$this->getCurrentDefinition() instanceof RowDefinition && !$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function groupAction()');
        }

        $this->getCurrentDefinition()->setGroupAction($name, $arg, $dependences);

        return $this;
    }

    public function extendingGroupAction($dependencies = array())
    {
        if (!$this->getCurrentDefinition() instanceof ColumnDefinition && !$this->getCurrentDefinition() instanceof RowDefinition && !$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function extendingGroupAction()');
        }

        $this->getCurrentDefinition()->setExtendingGroupAction($dependencies);

        return $this;
    }

    public function columnSpan($displayId, $number)
    {
        if (!$this->getCurrentDefinition() instanceof RowDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function columnSpan()');
        }

        $this->getCurrentDefinition()->setColSpan($displayId, $number);

        return $this;
    }

    public function rowSpan($displayIds)
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function rowSpan()');
        }

        $this->getCurrentDefinition()->setRowSpans($displayIds);

        return $this;
    }

    public function end()
    {
        if ($this->getCurrentDefinition() instanceof TableDefinition) {
            return $this->parent;
        }
        $this->setCurrentDefinition($this->getCurrentDefinition()->end());

        return $this;
    }

    public function configExport(array $allExportConfig)
    {
        // for each export, will get exportObject (if exist) and set all value in it, works as well without exportObject, for example CSV
        foreach ($allExportConfig as $key => $exportConfig) {

            $configObject = $this->getCurrentDefinition()->getExportConfig($key);

            foreach ($exportConfig as $keyParam => $parameter) {
                $setter = 'set' . ucfirst((string) Container::camelize($keyParam));
                if (method_exists($configObject, $setter)) {
                    $configObject->$setter($parameter);
                } else {
                    throw new \Exception('Setter for "' . $keyParam . '" does not exist in class "' . get_class($configObject) . '"');
                }
            }
            $config[$key] = $configObject;
        }
        $this->getCurrentDefinition()->setExportConfigs($config);

        return $this;
    }

    protected function getExcelExportConfigClass()
    {
        return '\Earls\RhinoReportBundle\Templating\ExportConfigurator\XlsExportConfigurator';
    }

    protected function getHtmlExportConfigClass()
    {
        return '\Earls\RhinoReportBundle\Templating\ExportConfigurator\HtmlExportConfigurator';
    }

    //define a set of data
    public function setData(array $data)
    {
        if (!$this->getCurrentDefinition() instanceof TableDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function setData()');
        }

        $dataObject = new \Earls\RhinoReportBundle\Module\Table\Util\DataObject($data);
        $this->getCurrentDefinition()->setData($dataObject);

        return $this;
    }

    public function position($position)
    {
        $this->getCurrentDefinition()->setPosition($position);

        return $this;
    }

    public function template($name)
    {
        $this->getCurrentDefinition()->setTemplate($name);

        return $this;
    }

    public function build()
    {
        $this->getDefinition()->build();
        return $this;
    }

    public function buildDefinition()
    {
        $defClass = $this->getDefinitionClass();
        $def = new $defClass($this->availableExport);
        $def->setTemplate('DefaultTemplate');

        return $def;
    }

    /**
     * 
     * @param string $selectedColumn    where you want ot apply the condition/format, displayId only
     * @param array $displayIds         column used in the condition
     * @param string $condition         condition you want, function sprintf is use behind
     * @param array $classes            classe you want to apply
     * @return RowDefinition
     * @throws \Exception
     */
    public function conditionalFormatting($selectedColumn, array $displayIds, $condition, array $classes)
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function rowSpan()');
        }

        $this->getCurrentDefinition()->addConditionalFormatting($selectedColumn, $displayIds, $condition, $classes);

        return $this;
    }

}
