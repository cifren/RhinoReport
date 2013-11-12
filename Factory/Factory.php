<?php

namespace Earls\RhinoReportBundle\Factory;

use Earls\RhinoReportBundle\Factory\ReportFactoryInterface;
use Earls\RhinoReportBundle\Definition\ReportDefinitionInterface;
use Earls\RhinoReportBundle\Util\Table\DataObjectInterface;

/*
 *  Earls\RhinoReportBundle\Factory\Factory
 *
 */

abstract class Factory implements ReportFactoryInterface
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

    public function getItem()
    {
        return $this->item;
    }

}
