<?php

namespace Earls\RhinoReportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Earls\RhinoReportBundle\DependencyInjection\Compiler\ActionPass
 * 
 * Adds all services with the tags "report.factory" arguments of the 
 * "report.entity.factory" service
 *
 * @author Cifren
 */
class FactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('report.definition.factory.manager')) {
            return;
        }
        // Builds an array with service IDs as keys and tag aliases as values
        $actions = array();
        $manager = $container->getDefinition('report.definition.factory.manager');
        foreach ($container->findTaggedServiceIds('report.factory') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            // add factory
            $manager->addMethodCall(
                'addFactory',
                array($alias, new Reference($serviceId))
            );
        }
    }
}
