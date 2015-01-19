<?php

namespace Earls\RhinoReportBundle\Module\Definition;

/**
 * Description of DatasetDefinition
 *
 * @author francis
 */
class DatasetDefinition
{

    protected $options;
    protected $data;

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

}
