<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Factory\Table\Action\Column\BasicHrefAction;

/**
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\HrefAction
 *
 */
class HrefAction extends BasicHrefAction
{

    protected function getUrl($parameters)
    {
        return $route = $this->router->generate($this->options['route'], $parameters, $this->options['absolute']);
    }

}
