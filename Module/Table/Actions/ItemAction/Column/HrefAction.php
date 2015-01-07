<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

use Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\BasicHrefAction;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\HrefAction
 *
 */
class HrefAction extends BasicHrefAction
{

    protected function getUrl($parameters)
    {
        return $route = $this->router->generate($this->options['route'], $parameters, $this->options['absolute']);
    }

}
