<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Generator;

use Symfony\Component\HttpFoundation\Response;
use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Table\Templating\DefaultTemplate\Simplifier\XlsReportSimplifier;

class XlsTableTemplateGenerator extends HtmlTemplateGenerator
{
    protected $file;
    protected $simplifier;

    public function __construct($templatingService, $templateTwig, XlsReportSimplifier $simplifier)
    {
        $this->simplifier = $simplifier;
        parent::__construct($templatingService, $templateTwig);
    }

    public function getResponse($filename, $table, $arg)
    {
        //trasnform table object into array easy to read for xml renderView
        $simpleTable = $this->simplifier->setTable($table)->getSimpleTable();
        $content = $this->renderView($this->twigTemplateName, array('table' => $simpleTable));
        //format your xml file, indentation etc...
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($content);
        $dom->formatOutput = true;

        //define content
        $contentType = 'application/ms-excel';

        //create response
        $response = new Response($dom->saveXML(), 200, array(
            'Content-Type' => $contentType,
            'Content-Disposition' => sprintf('attachment; filename=%s', $filename.'.xls'),
        ));

        return $response;
    }
}
