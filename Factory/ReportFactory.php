<?php

namespace Earls\RhinoReportBundle\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Earls\RhinoReportBundle\Model\Report;
use Earls\RhinoReportBundle\Factory\Factory;

/*
 *  Earls\RhinoReportBundle\Factory\ReportFactory
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
            $itemFactory = $this->container->get($itemDefinition->getFactoryService());

            $itemFactory->setDefinition($itemDefinition);
            $itemFactory->setData($this->data);

            $itemFactory->build();

            $newItem = $itemFactory->getItem();

            $this->item->addItem($newItem);
        }
    }

}
