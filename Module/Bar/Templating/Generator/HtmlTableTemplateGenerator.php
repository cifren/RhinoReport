<?php

namespace Earls\RhinoReportBundle\Module\Bar\Templating\Generator;

use Earls\RhinoReportBundle\Report\Templating\Generator\HtmlTemplateGenerator;

class HtmlTableTemplateGenerator extends HtmlTemplateGenerator
{

    protected $template;

    public function getResponse($nameFile, $barObject, $arg)
    {
        return $this->renderView($this->template, array_merge(array('barObject' => $barObject), $arg));
    }

}
