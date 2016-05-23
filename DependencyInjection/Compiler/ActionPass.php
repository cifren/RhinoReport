<?php

namespace Earls\RhinoReportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Earls\RhinoReportBundle\DependencyInjection\Compiler\ActionPass
 * 
 * Adds all services with the tags "..action.." arguments of the 
 * "report.table.extension" service
 *
 * @author Cifren
 */
class ActionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('report.table.extension')) {
            return;
        }

        //*********** ACTIONS ON COLUMN *********
        // Builds an array with service IDs as keys and tag aliases as values
        $actions = array();
        foreach ($container->findTaggedServiceIds('report.table.action.column') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            // Flip, because we want tag aliases (= action identifiers) as keys
            $actions[$alias] = $serviceId;
        }

        $container->getDefinition('report.table.extension')->replaceArgument(1, $actions);

        //*********** GROUP ACTIONS ON COLUMN *********
        $actions = array();
        foreach ($container->findTaggedServiceIds('report.table.group_action.column') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            // Flip, because we want tag aliases (= action identifiers) as keys
            $actions[$alias] = $serviceId;
        }

        $container->getDefinition('report.table.extension')->replaceArgument(2, $actions);

        //*********** ACTIONS ON ROW *********
        $actions = array();
        foreach ($container->findTaggedServiceIds('report.table.action.row') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            // Flip, because we want tag aliases (= action identifiers) as keys
            $actions[$alias] = $serviceId;
        }

        $container->getDefinition('report.table.extension')->replaceArgument(3, $actions);

        //*********** GROUP ACTIONS ON ROW *********
        $actions = array();
        foreach ($container->findTaggedServiceIds('report.table.group_action.row') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            // Flip, because we want tag aliases (= action identifiers) as keys
            $actions[$alias] = $serviceId;
        }

        $container->getDefinition('report.table.extension')->replaceArgument(4, $actions);

        //*********** ACTIONS ON GROUP *********
        $actions = array();
        foreach ($container->findTaggedServiceIds('report.table.action.group') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;

            // Flip, because we want tag aliases (= action identifiers) as keys
            $actions[$alias] = $serviceId;
        }

        $container->getDefinition('report.table.extension')->replaceArgument(5, $actions);

        //*********** GROUP ACTIONS ON GROUP *********
        $actions = array();
        foreach ($container->findTaggedServiceIds('report.table.group_action.group') as $serviceId => $tag) {
            $alias = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;
            // Flip, because we want tag aliases (= action identifiers) as keys
            $actions[$alias] = $serviceId;
        }

        $container->getDefinition('report.table.extension')->replaceArgument(6, $actions);
    }
}
