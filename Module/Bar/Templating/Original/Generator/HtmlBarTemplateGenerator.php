<?php

namespace Earls\RhinoReportBundle\Module\Bar\Templating\Original\Generator;

use Earls\RhinoReportBundle\Report\Templating\Original\Generator\HtmlTemplateGenerator;
use Earls\RhinoReportBundle\Module\Bar\BarObject\Bar;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

class HtmlBarTemplateGenerator extends HtmlTemplateGenerator
{

    protected $template;

    public function getResponse($nameFile, ModuleObject $barObject, $arg)
    {
        return $this->renderView($this->template, array_merge(array('bar' => $barObject), $arg));
    }

    public function getData(ModuleObject $barObject)
    {
        $arrayData['labels'] = $barObject->getLabels();
        $arrayData['datasets'] =array();
        foreach ($barObject->getDatasets() as $dataset){
            $arrayData['datasets'][] = $dataset->getData();
        }
        
        return $arrayData;
    }

}
