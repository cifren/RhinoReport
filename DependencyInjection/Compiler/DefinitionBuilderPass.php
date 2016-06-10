<?php

namespace Earls\RhinoReportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Earls\RhinoReportBundle\DependencyInjection\Compiler\DefinitionBuilderPass.
 *
 * Adds all services with the tags "report.definition.builder" as
 * arguments of the "report.definition.builder" service
 *
 * @author Francis
 */
class DefinitionBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('report.definition.builder')) {
            return;
        }

        // Builds an array with service IDs as keys and tag aliases as values
        $definition = $container->getDefinition('report.definition.builder');
        foreach ($container->findTaggedServiceIds('report.definition.builder') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            // Flip, because we want tag aliases (= action identifiers) as keys
            $definition->addMethodCall(
                'addBuilder',
                array($alias, new Reference($serviceId))
            );
        }
    }
}
