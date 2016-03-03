<?php

namespace Earls\RhinoReportBundle\Report\Listener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Earls\RhinoReportBundle\Entity\RhnReportDefinition;
use Earls\RhinoReportBundle\Report\Factory\ReportFactoryInterface;

/**
 * Earls\RhinoReportBundle\Report\Listener\RhnReportDefinitionListener
 */
class RhnReportDefinitionListener
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
        if (!$entity instanceof RhnReportDefinition) {
            return;
        }
        $entity->setObjectFactory($this->reportFactory);
    }
}
