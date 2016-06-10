<?php

namespace Earls\RhinoReportBundle\Module\Table\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Earls\RhinoReportBundle\Entity\RhnTblMainDefinition;
use Earls\RhinoReportBundle\Report\Factory\ReportFactoryInterface;

/**
 * Earls\RhinoReportBundle\Module\Table\Listener\EntityListener.
 **/
class EntityListener
{
    protected $reportFactory;

    public function __construct(ReportFactoryInterface $reportFactory)
    {
        $this->reportFactory = $reportFactory;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if (!$entity instanceof RhnTblMainDefinition) {
            return;
        }

        $entity->setObjectFactory($this->reportFactory);
    }
}
