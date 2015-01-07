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
class TemplatingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loader = $container->getDefinition('twig.loader.filesystem');
        $loader->addMethodCall('addPath', array(__DIR__.'/../../Module/Table/Resources/views', 'RhinoReportTable'));
        $loader->addMethodCall('addPath', array(__DIR__.'/../../Report/Resources/views', 'RhinoReportReport'));
    }
}
