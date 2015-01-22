<?php

namespace Earls\RhinoReportBundle\Module\Bar\Definition;

use Earls\RhinoReportBundle\Report\Definition\ModuleDefinition;

/**
 * Description of newPHPClass
 *
 * @author francis
 */
class BarDefinition extends ModuleDefinition
{

    protected $options = array();
    protected $labelColumn;
    protected $dataColumns;

    public function __construct($factoryServiceName = "report.bar.factory", $id = 'bar')
    {
        parent::__construct($factoryServiceName, $id);
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function setLabelColumn($labelColumn)
    {
        $this->labelColumn = $labelColumn;
        return $this;
    }

    public function setDataColumns(array $dataColumns)
    {
        $this->dataColumns = $dataColumns;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getLabelColumn()
    {
        return $this->labelColumn;
    }

    public function getDataColumns()
    {
        return $this->dataColumns;
    }

}
