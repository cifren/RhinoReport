<?php

namespace Earls\RhinoReportBundle\Report\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Earls\RhinoReportBundle\Report\ReportObject\Report;
use Earls\RhinoReportBundle\Report\Factory\AbstractFactory;
use Earls\RhinoReportBundle\Report\Factory\ReportObjectFactoryCollection;

/**
 *  Earls\RhinoReportBundle\Report\Factory\ReportFactory
 *
 */
class ReportFactory extends AbstractFactory
{


    public function __construct()
    {
        $this->item = new Report();
    }

    public function build()
    {
        foreach ($this->getDefinition()->getItems() as $itemDefinition) {
            $itemFactory = $itemDefinition->getObjectFactory();

            $itemFactory->setDefinition($itemDefinition);
            $itemFactory->setData($this->getData());
            $itemFactory->build();

            $newItem = $itemFactory->getItem();

            $this->getItem()->addItem($newItem);
        }
    }

}
