<?php

namespace Earls\RhinoReportBundle\Module\Table\Definition;

use Earls\RhinoReportBundle\Report\Definition\ReportDefinitionBuilder;
use Earls\RhinoReportBundle\Report\Definition\ReportDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;
use Symfony\Component\DependencyInjection\Container;

/*
 * Earls\RhinoReportBundle\Module\Table\Definition\TableDefinitionBuilder
 */

class TableDefinitionBuilder
{

    protected $tableDefinition;
    protected $parent;
    protected $currentDefinition;
    protected $availableExport;

    public function __construct(ReportDefinition $reportDefinition = null, ReportDefinitionBuilder $parentBuilder = null, $idTable = null)
    {
        $this->availableExport = array('html' => $this->getHtmlExportConfigClass(), 'excel' => $this->getExcelExportConfigClass());

        $this->tableDefinition = new TableDefinition($this->availableExport, $idTable);
        $this->tableDefinition->setParent($reportDefinition);

        $this->currentDefinition = $this->tableDefinition;

        $this->parent = $parentBuilder;
    }

    public function getTableDefinition()
    {
        return $this->tableDefinition;
    }

    public function head()
    {
        if (!$this->currentDefinition instanceof TableDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition", "' . get_class($this->currentDefinition) . '" given in function head()');

        $this->currentDefinition = $this->currentDefinition->getHeadDefinition();

        return $this;
    }

    public function body()
    {
        if (!$this->currentDefinition instanceof TableDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition", "' . get_class($this->currentDefinition) . '" given in function body()');

        $this->currentDefinition = $this->currentDefinition->getBodyDefinition();

        return $this;
    }

    public function headColumns(array $columnNames)
    {
        if (!$this->currentDefinition instanceof HeadDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition", "' . get_class($this->currentDefinition) . '" given in function headColumns()');

        $this->currentDefinition = $this->currentDefinition->setColumns($columnNames);

        return $this;
    }

    public function group($id)
    {
        if (!$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function group()');

        $this->currentDefinition = $this->currentDefinition->addGroup($id, $this->availableExport);

        return $this;
    }

    public function row()
    {
        if (!$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function row()');

        $this->currentDefinition = $this->currentDefinition->addRow(array(), $this->availableExport);

        return $this;
    }

    public function rowUnique()
    {
        if (!$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function rowUnique()');

        $this->currentDefinition = $this->currentDefinition->addRow(array('unique' => true), $this->availableExport);

        return $this;
    }

    public function attr(array $attributes = array())
    {
        if (!$this->currentDefinition instanceof TableDefinition and !$this->currentDefinition instanceof RowDefinition and !$this->currentDefinition instanceof ColumnDefinition and !$this->currentDefinition instanceof HeadDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition", "' . get_class($this->currentDefinition) . '" given in function attr()');

        if (isset($attributes['class']) && !is_array($attributes['class'])) {
            throw new \Exception('Attribute `class´ should be an array on element `' . $this->currentDefinition->getPath() . '´');
        }
        if (isset($attributes['style']) && !is_array($attributes['style'])) {
            throw new \Exception('Attribute `style´ should be an array on element `' . $this->currentDefinition->getPath() . '´');
        }
        $this->currentDefinition->setAttributes($attributes);

        return $this;
    }

    public function groupBy($id)
    {
        if (!$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function groupBy()');

        $this->currentDefinition->setGroupBy($id);

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
        if (!$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function groupBy()');

        if (!is_array($columns)) {
            $columns = array($columns);
        }
        $this->currentDefinition->setOrderBy($columns);

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
        if (!$this->currentDefinition instanceof RowDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition", "' . get_class($this->currentDefinition) . '" given in function columnData()');

        if (!$dataId) {
            //dont close columnDefinition
            $this->currentDefinition->setColumn($displayId, $type, $this->availableExport);
            $this->currentDefinition = $this->currentDefinition->getColumn($displayId);

            return $this;
        }

        //close ColumnDefinition
        $this->currentDefinition->setColumn($displayId, $type, $this->availableExport, $dataId);

        return $this;
    }

    //set data for column
    public function baseData($type, $arg)
    {
        if (!$this->currentDefinition instanceof ColumnDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition", "' . get_class($this->currentDefinition) . '" given in function baseData()');

        $this->currentDefinition->setBaseData($type, $arg);

        return $this;
    }

    //for excel purpose, freeze data
    public function baseValue(array $arg)
    {
        if (!$this->currentDefinition instanceof ColumnDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition", "' . get_class($this->currentDefinition) . '" given in function baseValue()');

        $this->currentDefinition->addAction('basevalue', $arg);

        return $this;
    }

    public function formulaExcel($formula, $columns)
    {
        if (!$this->currentDefinition instanceof ColumnDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition", "' . get_class($this->currentDefinition) . '" given in function formulaExcel()');

        if (!$this->currentDefinition->getType() == ColumnDefinition::TYPE_DATA)
            throw new \Exception('Function "formatExcel" can\'t work on columnData');

        $this->currentDefinition->setFormulaExcel(array('formula' => $formula, 'columns' => $columns));

        return $this;
    }

    public function action($name, array $arg)
    {
        if (!$this->currentDefinition instanceof ColumnDefinition && !$this->currentDefinition instanceof RowDefinition && !$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function columnAction()');

        $this->currentDefinition->addAction($name, $arg);

        return $this;
    }

    public function groupAction($name, array $arg, $dependences = array())
    {
        if (!$this->currentDefinition instanceof ColumnDefinition && !$this->currentDefinition instanceof RowDefinition && !$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function groupAction()');

        $this->currentDefinition->setGroupAction($name, $arg, $dependences);

        return $this;
    }

    public function extendingGroupAction($dependencies = array())
    {
        if (!$this->currentDefinition instanceof ColumnDefinition && !$this->currentDefinition instanceof RowDefinition && !$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function extendingGroupAction()');

        $this->currentDefinition->setExtendingGroupAction($dependencies);

        return $this;
    }

    public function columnSpan($displayId, $number)
    {
        if (!$this->currentDefinition instanceof RowDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition", "' . get_class($this->currentDefinition) . '" given in function columnSpan()');

        $this->currentDefinition->setColSpan($displayId, $number);

        return $this;
    }

    public function rowSpan($displayIds)
    {
        if (!$this->currentDefinition instanceof GroupDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->currentDefinition) . '" given in function rowSpan()');

        $this->currentDefinition->setRowSpans($displayIds);

        return $this;
    }

    public function end()
    {
        if ($this->currentDefinition instanceof TableDefinition)
            return $this->parent;
        $this->currentDefinition = $this->currentDefinition->end();

        return $this;
    }

    public function configExport(array $allExportConfig)
    {
        // for each export, will get exportObject (if exist) and set all value in it, works as well without exportObject, for example CSV
        foreach ($allExportConfig as $key => $exportConfig) {

            $configObject = $this->currentDefinition->getExportConfig($key);

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
        $this->currentDefinition->setExportConfigs($config);

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
        if (!$this->currentDefinition instanceof TableDefinition)
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition", "' . get_class($this->currentDefinition) . '" given in function setData()');

        $dataObject = new \Earls\RhinoReportBundle\Module\Table\Util\DataObject($data);
        $this->currentDefinition->setData($dataObject);

        return $this;
    }

    public function build()
    {
        return $this->getTableDefinition()->build();
    }

}
