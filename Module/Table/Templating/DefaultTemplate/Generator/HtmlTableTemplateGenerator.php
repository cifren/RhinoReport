<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Generator;

use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Simplifier\HtmlReportSimplifier;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

class HtmlTableTemplateGenerator extends HtmlTemplateGenerator
{
    protected $twigResponseTemplateName;

    public function __construct($templatingService, $twigTemplateName, $twigResponseTemplateName, $uniqueBlockName = null)
    {
        $this->templatingService = $templatingService;
        $this->twigTemplateName = $twigTemplateName;
        $this->twigResponseTemplateName = $twigResponseTemplateName;
        $this->uniqueBlockName = $uniqueBlockName;
    }

    public function getResponse($nameFile, $table, $arg)
    {
        $htmlConfigExport = $table->getDefinition()->getExportConfig('html');
        if ($htmlConfigExport->getResponse()['uniqueBlock'] === false) {
            $displayUniqueBloc = false;
        } else {
            $displayUniqueBloc = true;
        }

        return $this->renderView($this->twigResponseTemplateName, array('element' => $this->getTemplating($table, null, null), 'uniqueBlock' => $displayUniqueBloc));
    }

    public function getData(ModuleObject $object)
    {
        $simplifier = new HtmlReportSimplifier($object);
        $simpleTable = $simplifier->getSimpleTable();

        return $simpleTable;
    }
}
