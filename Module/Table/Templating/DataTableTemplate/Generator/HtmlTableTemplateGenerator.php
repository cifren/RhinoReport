<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Generator;

use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Table\Templating\SystemTemplate\Simplifier\HtmlReportSimplifier;
use Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\HtmlTemplateTable;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

class HtmlTableTemplateGenerator extends HtmlTemplateGenerator
{

    protected $template;

    public function getResponse($nameFile, ModuleObject $table, $arg)
    {
        return $this->renderView($this->template, array_merge(array('table' => $simpleTable, 'js' => true, 'css' => true), $arg));
    }

    public function getTemplating(ModuleObject $reportObject, $remoteUrl, $exportUrl)
    {
        $template = new HtmlTemplateTable();
        $transformedObject = $this->applyTransformers($reportObject);
        
        $simplifier = new HtmlReportSimplifier($reportObject);
        $simpleTable = $simplifier->getSimpleTable();
        $template->setSimpleTable($simpleTable);
        
        $template->setModuleObject($transformedObject);
        $template->setRemoteUrl($remoteUrl);
        $template->setExportUrl($exportUrl);
        
        return $template;
    }

}
