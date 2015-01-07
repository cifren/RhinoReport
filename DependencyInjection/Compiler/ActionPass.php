<?php

namespace Earls\RhinoReportBundle\DependencyInjection\Compiler;

/*
 * Earls\RhinoReportBundle\DependencyInjection\Compiler\ActionPass
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds all services with the tags "form.type" and "form.type_guesser" as
 * arguments of the "form.extension" service
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
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
        
        $loader = $container->getDefinition('twig.loader.filesystem');
        $loader->addMethodCall('addPath', array(__DIR__.'/../../Report/Resources/views', 'RhinoReportReport'));
        $loader->addMethodCall('addPath', array(__DIR__.'/../../Module/Table/Resources/views', 'RhinoReportTable'));
    }
}
