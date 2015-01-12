<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\Generator;

use Earls\RhinoReportBundle\Report\Templating\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Table\Templating\Simplifier\HtmlReportSimplifier;

class HtmlTableTemplateGenerator extends HtmlTemplateGenerator
{

    protected $template;

    public function getResponse($nameFile, $table, $arg)
    {
        //$nameFile not used for html
        $simplifier = new HtmlReportSimplifier($table);
        $simpleTable = $simplifier->getSimpleTable();

        return $this->renderView($this->template, array_merge(array('table' => $simpleTable, 'js' => true, 'css' => true), $arg));
    }

}
