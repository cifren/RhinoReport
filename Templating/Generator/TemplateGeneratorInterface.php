<?php

namespace Earls\RhinoReportBundle\Templating\Generator;

/*
 *  Earls\RhinoReportBundle\Templating\Generator\TemplateGeneratorInterface
 *
 */
interface TemplateGeneratorInterface
{
    public function getResponse($nameFile, $object, $arg);
}
