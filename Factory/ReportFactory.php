<?php

namespace Fuller\ReportBundle\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Fuller\ReportBundle\Model\Report;
use Fuller\ReportBundle\Factory\Factory;

/*
 *  Fuller\ReportBundle\Factory\ReportFactory
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
