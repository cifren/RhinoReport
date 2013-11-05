<?php

namespace Fuller\ReportBundle\Templating\Generator\Table;

use Fuller\ReportBundle\Templating\Generator\HtmlTemplateGenerator;
use Fuller\ReportBundle\Templating\Simplifier\Table\HtmlReportSimplifier;

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
