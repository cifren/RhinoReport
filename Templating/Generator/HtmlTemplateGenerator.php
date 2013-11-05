<?php

namespace Fuller\ReportBundle\Templating\Generator;

abstract class HtmlTemplateGenerator implements TemplateGeneratorInterface
{

    protected $templatingService;
    protected $template;

    public function __construct($templatingService, $templateTwig)
    {
        $this->templatingService = $templatingService;
        $this->template = $templateTwig;
    }

    public function getResponse($nameFile, $object, $arg)
    {
        //$nameFile not used for html
        return $this->renderView($this->template, array('object' => $object));
    }

    protected function renderView($view, array $parameters = array())
    {
        return $this->templatingService->render($view, $parameters);
    }

}
