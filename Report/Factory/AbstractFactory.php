<?php

namespace Earls\RhinoReportBundle\Report\Factory;

use Earls\RhinoReportBundle\Report\Definition\ReportDefinitionInterface;
use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;

/*
 *  Earls\RhinoReportBundle\Report\Factory\AbstractFactory
 *
 */

abstract class AbstractFactory implements ReportFactoryInterface
{
    protected $data;
    protected $definition;
    protected $item;

    public function setDefinition(ReportDefinitionInterface $definition)
    {
        $this->definition = $definition;

        return $this;
    }

    public function setData(DataObjectInterface $data)
    {
        $this->data = $data;
    }

    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    public function getData()
    {
        //select data from id if exist
        if ($this->data->getDatum($this->getDefinition()->getDisplayId())) {
            return new DataObject($this->data->getDatum($this->getDefinition()->getDisplayId()));
        }

        return $this->data;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function getItem()
    {
        return $this->item;
    }
}
