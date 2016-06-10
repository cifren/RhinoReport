<?php

namespace Earls\RhinoReportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TemplatingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $loader = $container->getDefinition('twig.loader.filesystem');
        $loader->addMethodCall('addPath', array(__DIR__.'/../../Module/Table/Resources/views', 'RhinoReportTable'));
        $loader->addMethodCall('addPath', array(__DIR__.'/../../Report/Resources/views', 'RhinoReportReport'));
    }
}
