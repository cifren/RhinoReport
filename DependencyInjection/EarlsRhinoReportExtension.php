<?php

namespace Earls\RhinoReportBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EarlsRhinoReportExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../'));
        $loader->load('Report/Resources/config/services.yml');
        $loader->load('Module/Table/Resources/config/services.yml');
    }

    public function getAlias()
    {
        return 'earls_rhino_report';
    }

}
