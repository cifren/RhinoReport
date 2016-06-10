<?php

namespace Earls\RhinoReportBundle\Report\Factory;

use Earls\RhinoReportBundle\Report\ReportObject\Report;

/**
 *  Earls\RhinoReportBundle\Report\Factory\ReportFactory.
 */
class ReportFactory extends AbstractFactory
{
    protected $definitionFactoryManager;

    public function __construct($definitionFactoryManager)
    {
        $this->definitionFactoryManager = $definitionFactoryManager;
        $this->item = new Report();
    }

    public function build()
    {
        $this->setObjectFactory($this->getDefinition());

        foreach ($this->getDefinition()->getItems() as $itemDefinition) {
            $itemFactory = $itemDefinition->getObjectFactory();

            $itemFactory->setDefinition($itemDefinition);
            $itemFactory->setData($this->getData());
            $itemFactory->build();

            $newItem = $itemFactory->getItem();

            $this->getItem()->addItem($newItem);
        }
    }

    protected function setObjectFactory($definition)
    {
        $this->definitionFactoryManager->setObjectFactory($definition);
    }
}
