<?php

namespace Fuller\ReportBundle\Factory;

use Fuller\ReportBundle\Factory\ReportFactoryInterface;
use Fuller\ReportBundle\Definition\ReportDefinitionInterface;
use Fuller\ReportBundle\Util\Table\DataObjectInterface;

/*
 *  Fuller\ReportBundle\Factory\Factory
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
