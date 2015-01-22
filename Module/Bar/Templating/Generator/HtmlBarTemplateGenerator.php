<?php

namespace Earls\RhinoReportBundle\Module\Bar\Templating\Generator;

use Earls\RhinoReportBundle\Report\Templating\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Bar\BarObject\Bar;

class HtmlBarTemplateGenerator extends HtmlTemplateGenerator
{

    protected $template;

    public function getResponse($nameFile, $barObject, $arg)
    {
        return $this->renderView($this->template, array_merge(array('bar' => $barObject), $arg));
    }

}
