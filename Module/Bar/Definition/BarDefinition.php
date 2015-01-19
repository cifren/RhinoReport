<?php

namespace Earls\RhinoReportBundle\Module\Definition;

use Earls\RhinoReportBundle\Report\Definition\ModuleDefinition;

/**
 * Description of newPHPClass
 *
 * @author francis
 */
class BarDefinition extends ModuleDefinition
{

    protected $options = array();
    protected $data = array();
    protected $dataset = array();
    protected $labels = array();

    public function __construct($factoryServiceName = "report.bar.factory")
    {
        parent::__construct($factoryServiceName);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataset()
    {
        return $this->dataset;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setDataset($dataset)
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;
        return $this;
    }

}
