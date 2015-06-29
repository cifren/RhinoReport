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
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds all services with the tags "form.type" and "form.type_guesser" as
 * arguments of the "form.extension" service
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class DefinitionBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('report.definition.builder')) {
            return;
        }

        //*********** ACTIONS ON COLUMN *********
        // Builds an array with service IDs as keys and tag aliases as values
        $definition=$container->getDefinition('report.definition.builder');
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
