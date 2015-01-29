<?php

namespace Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Generator;

use Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Model\ModuleTemplate;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

/**
 * Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Generator\HtmlTemplateGenerator
 */
abstract class HtmlTemplateGenerator implements TemplateGeneratorInterface
{

    protected $templatingService;
    protected $template;

    public function __construct($templatingService, $templateTwig)
    {
        $this->templatingService = $templatingService;
        $this->template = $templateTwig;
    }

    public function getResponse($nameFile, ModuleObject $object, $arg)
    {
        //$nameFile not used for html
        return $this->renderView($this->template, array('object' => $object));
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

}
