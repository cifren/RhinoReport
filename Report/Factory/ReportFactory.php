<?php

namespace Earls\RhinoReportBundle\Report\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Earls\RhinoReportBundle\Report\ReportObject\Report;
use Earls\RhinoReportBundle\Report\Factory\Factory;

/*
 *  Earls\RhinoReportBundle\Report\Factory\ReportFactory
 *
 */

class ReportFactory extends Factory
{

    protected $data;
    protected $definition;
    protected $item;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->item = new Report();
    }

    public function build()
    {
        foreach ($this->definition->getItems() as $itemDefinition) {
            $itemFactory = $this->container->get($itemDefinition->getFactoryServiceName());

            $itemFactory->setDefinition($itemDefinition);
            $itemFactory->setData($this->data);
            $itemFactory->build();

            $newItem = $itemFactory->getItem();

            $this->item->addItem($newItem);
        }
    }

}
