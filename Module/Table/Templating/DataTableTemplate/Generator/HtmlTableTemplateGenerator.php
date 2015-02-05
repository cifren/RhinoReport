<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Generator;

use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Simplifier\DataReportSimplifier;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

class HtmlTableTemplateGenerator extends HtmlTemplateGenerator
{

    public function getResponse($nameFile, $table, $arg)
    {
        return $this->renderView($this->twigTemplateName, array('element' => $this->getTemplating($table, null, null)));
    }

    public function getData(ModuleObject $object)
    {
        $simplifier = new DataReportSimplifier($object);
        $simpleTable = $simplifier->getSimpleTable();

        return $simpleTable;
    }

}
