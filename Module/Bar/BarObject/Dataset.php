<?php

namespace Earls\RhinoReportBundle\Module\Bar\BarObject;

use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

/**
 * Description of BarObject
 *
 * @author cifren
 */
class Dataset extends ModuleObject
{

    protected $options;
    protected $data;
    protected $label;

    public function __construct($label, $data, $options = array())
    {
        $this->setLabel($label);
        $this->setData($data);
        $this->setOptions($options);
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

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getLabel()
    {
        return $this->label;
    }

}
