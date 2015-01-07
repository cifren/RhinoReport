<?php

namespace Earls\RhinoReportBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Earls\RhinoReportBundle\DependencyInjection\Compiler\ActionPass;
use Earls\RhinoReportBundle\DependencyInjection\Compiler\TemplatingPass;

class EarlsRhinoReportBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ActionPass());
        $container->addCompilerPass(new TemplatingPass());
    }

}
