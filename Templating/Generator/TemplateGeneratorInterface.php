<?php

namespace Fuller\ReportBundle\Templating\Generator;

/*
 *  Fuller\ReportBundle\Templating\Generator\TemplateGeneratorInterface
 *
 */
interface TemplateGeneratorInterface
{
    public function getResponse($nameFile, $object, $arg);
}
