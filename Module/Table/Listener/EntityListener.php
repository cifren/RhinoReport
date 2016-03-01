<?php 
namespace Earls\RhinoReportBundle\Module\Table\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Earls\RhinoReportBundle\Module\Table\Entity\RhnTableDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Listener\EntityListener
 **/ 
class EntityListener
{
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if (!$entity instanceof RhnTableDefinition) {
            return;
        }

        
    }
}