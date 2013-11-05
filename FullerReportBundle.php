<?php

namespace Fuller\ReportBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Fuller\ReportBundle\DependencyInjection\Compiler\ActionPass;

class FullerReportBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ActionPass());
    }

}
