<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Generator;
    
use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Simplifier\DataReportSimplifier;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

class HtmlTableTemplateGenerator extends HtmlTemplateGenerator
{

    protected $template;

    public function getResponse($nameFile, ModuleObject $table, $arg)
    {
        return $this->renderView($this->template, array_merge(array('table' => $simpleTable, 'js' => true, 'css' => true), $arg));
    }
    
    public function getData(ModuleObject $object)
    {
        $simplifier = new DataReportSimplifier($object);
        $simpleTable = $simplifier->getSimpleTable();
        
        return $simpleTable;
    }

}
