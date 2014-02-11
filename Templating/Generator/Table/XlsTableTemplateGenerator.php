<?php

namespace Earls\RhinoReportBundle\Templating\Generator\Table;

use Symfony\Component\HttpFoundation\Response;
use Earls\RhinoReportBundle\Templating\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Templating\Simplifier\Table\XlsReportSimplifier;

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




// Code ...

// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

  
    
    public function getResponse($filename, $table, $arg)
    {
        set_time_limit(600);
    // Script start

        //transform table object into array easy to read for xml renderView
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
