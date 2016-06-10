<?php

namespace Earls\RhinoReportBundle\Module\Table\Util;

/**
 * Earls\RhinoReportBundle\Module\Table\Util\DataObject.
 */
class DataObject implements DataObjectInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->setData($data);
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getDatum($name)
    {
        return (isset($this->data[$name])) ? $this->data[$name] : null;
    }
}
