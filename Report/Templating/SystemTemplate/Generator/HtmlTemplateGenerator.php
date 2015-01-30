<?php

namespace Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator;

use Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Model\ModuleTemplate;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

/**
 * Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Generator\HtmlTemplateGenerator
 */
abstract class HtmlTemplateGenerator implements TemplateGeneratorInterface
{

    protected $templatingService;
    protected $twigTemplateName;
    protected $uniqueBlockName;

    public function __construct($templatingService, $twigTemplateName, $uniqueBlockName = null)
    {
        $this->templatingService = $templatingService;
        $this->twigTemplateName = $twigTemplateName;
        $this->uniqueBlockName = $uniqueBlockName;
    }

    public function getResponse($nameFile, ModuleObject $object, $arg)
    {
        //$nameFile not used for html
        return $this->renderView($this->twigTemplateName, array('object' => $object));
    }

    protected function renderView($view, array $parameters = array())
    {
        return $this->templatingService->render($view, $parameters);
    }

    public function getTemplating(ModuleObject $reportObject, $remoteUrl, $exportUrl)
    {
        $template = new ModuleTemplate();
        $transformedObject = $this->applyTransformers($reportObject);
        $template->setOptions($reportObject->getOptions());
        $template->setModuleObject($transformedObject);
        $template->setRemoteUrl($remoteUrl);
        $template->setExportUrl($exportUrl);
        $template->setData($this->getData($reportObject));
        $template->setTemplatingName($reportObject->getTemplate());

        return $template;
    }

    protected function applyTransformers(ModuleObject $object)
    {
        return $object;
    }

    public function getData(ModuleObject $object)
    {
        return array();
    }

    public function getTwigTemplateName()
    {
        return $this->twigTemplateName;
    }

    public function getUniqueBlockName()
    {
        return $this->uniqueBlockName;
    }

    public function setTwigTemplateName($twigTemplateName)
    {
        $this->twigTemplateName = $twigTemplateName;
        return $this;
    }

    public function setUniqueBlockName($uniqueBlockName)
    {
        $this->uniqueBlockName = $uniqueBlockName;
        return $this;
    }

}
