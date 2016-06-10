<?php

namespace Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Generator;

use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator;

/**
 * Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Generator\HtmlReportTemplateGenerator.
 */
class HtmlReportTemplateGenerator extends HtmlTemplateGenerator
{
    protected $templatingService;
    protected $twigTemplateName;
    protected $twigTemplateFilterName;

    public function __construct($templatingService, $twigTemplateName, $twigTemplateFilterName)
    {
        $this->templatingService = $templatingService;
        $this->twigTemplateName = $twigTemplateName;
        $this->twigTemplateFilterName = $twigTemplateFilterName;
    }

    public function getResponse($nameFile, $object, $arg, $id = null)
    {
        if ($id == 'filter') {
            $filterObject = $object->getFilter();

            return $this->getFilterResponse($filterObject, $arg);
        }

        return $this->getAllReportResponse($object, $arg);
    }

    protected function getFilterResponse($filterObject, $arg)
    {
        return $this->renderView($this->twigTemplateFilterName, array('filter' => $filterObject));
    }

    protected function getAllReportResponse($reportObject, $arg)
    {
        return $this->renderView($this->getTwigTemplateName(), array('template' => $this->getTemplating($reportObject, null, null)));
    }
}
