<?php

namespace Earls\RhinoReportBundle\Definition;

/*
 *  Earls\RhinoReportBundle\Definition\ReportDefinition
 *
 */

class ReportDefinition implements ReportDefinitionInterface
{

    protected $items;
    protected $factoryService;

    public function __construct()
    {
        $this->setFactoryService("report.factory");
    }

    public function setFactoryService($serviceName)
    {
        $this->factoryService = $serviceName;

        return $this;
    }

    public function getFactoryService()
    {
        return $this->factoryService;
    }

    public function setItem($id, $item)
    {
        return $this->items[$id] = $item;
    }

    public function getItem($id)
    {
        return $this->items[$id];
    }

    public function addItem($item)
    {
        if (!$item->getId()) {
            throw new \UnexpectedValueException('The Id must not be empty');
        }

        if (isset($this->items[$item->getId()])) {
            throw new \UnexpectedValueException('This Id \'' . $item->getId() . '\' is already used');
        }

        $this->items[$item->getId()] = $item;

        return $this;
    }

    public function removeItem($id)
    {
        unset($this->items[$id]);
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

}
