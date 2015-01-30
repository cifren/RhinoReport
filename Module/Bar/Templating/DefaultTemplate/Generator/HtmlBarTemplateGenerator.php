<?php

namespace Earls\RhinoReportBundle\Module\Bar\Templating\DefaultTemplate\Generator;

use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator;
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
        foreach ($barObject->getDatasets() as $key => $dataset){
            $arrayData['datasets'][$key]['label'] = $dataset->getLabel();
            $arrayData['datasets'][$key]['dataset'] = $dataset->getData();
            $arrayData['datasets'][$key]['options'] = $dataset->getOptions();
        }
        
        return $arrayData;
    }

}
