<?php

namespace Fuller\ReportBundle\Templating\Generator\Table;

use Exporter\Handler;
use Symfony\Component\HttpFoundation\Response;
use Fuller\ReportBundle\Templating\Simplifier\Table\DataReportSimplifier;

class CsvTableTemplateGenerator
{

    public function getResponse($nameFile, $table)
    {
        //$nameFile not used for html

        $simplifier = new DataReportSimplifier($table);
        $simpleTable = $simplifier->getSimpleTable();

        $sourceIterator = new \Exporter\Source\ArraySourceIterator($simpleTable);

        $privateFilename = sprintf('%s/%s', sys_get_temp_dir(), uniqid('report_export_', true));

        $writer = new \Exporter\Writer\CsvWriter($privateFilename, ',', '"', "", true);
        $contentType = 'text/csv';

        $handler = Handler::create($sourceIterator, $writer);
        $handler->export();

        $response = new Response(file_get_contents($privateFilename), 200, array(
            'Content-Type' => $contentType,
            'Content-Disposition' => sprintf('attachment; filename=%s', $nameFile)
        ));

        return $response;
    }

}
