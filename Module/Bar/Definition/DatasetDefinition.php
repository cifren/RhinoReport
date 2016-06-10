<?php

namespace Earls\RhinoReportBundle\Module\Bar\Definition;

/**
 * Description of newPHPClass.
 *
 * @author francis
 */
class DatasetDefinition
{
    protected $labelColumn;
    protected $dataColumn;
    protected $options = array();

    public function __construct($columnData, $labelData, $options)
    {
        $this->setDataColumn($columnData);
        $this->setLabelColumn($labelData);
        $this->setOptions($options);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getLabelColumn()
    {
        return $this->labelColumn;
    }

    public function getDataColumn()
    {
        return $this->dataColumn;
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

    public function setDataColumn($dataColumn)
    {
        $this->dataColumn = $dataColumn;

        return $this;
    }
}
