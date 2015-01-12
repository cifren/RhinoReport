<?php

namespace Earls\RhinoReportBundle\Report\Factory;

use Earls\RhinoReportBundle\Report\Factory\ReportFactoryInterface;
use Earls\RhinoReportBundle\Report\Definition\ReportDefinitionInterface;
use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;

/*
 *  Earls\RhinoReportBundle\Report\Factory\Factory
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
