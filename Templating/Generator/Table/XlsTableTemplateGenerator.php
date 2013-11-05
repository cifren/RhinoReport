<?php

namespace Fuller\ReportBundle\Templating\Generator\Table;

use Symfony\Component\HttpFoundation\Response;
use Fuller\ReportBundle\Templating\Generator\HtmlTemplateGenerator;
use Fuller\ReportBundle\Templating\Simplifier\Table\XlsReportSimplifier;

class XlsTableTemplateGenerator extends HtmlTemplateGenerator
{

    protected $file;
    protected $template;
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

        //$privateFilename = sprintf('%s/%s', sys_get_temp_dir(), uniqid('report_table_xls_export_', true));
        
        //create a temporary file
//        $this->file = fopen($privateFilename, 'w', false);
        
        $content = $this->renderView($this->template, array('table' => $simpleTable));
        
        //format your xml file, indentation etc...
        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($content);
        $dom->formatOutput = TRUE;
        
        //define content
        $contentType = 'application/ms-excel';
        
        //create response
        $response = new Response($dom->saveXML(), 200, array(
            'Content-Type' => $contentType,
            'Content-Disposition' => sprintf('attachment; filename=%s', $filename . '.xls')
        ));

        return $response;
    }

}
