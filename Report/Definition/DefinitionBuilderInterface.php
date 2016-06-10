<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Description of DefinitionBuilderInterface.
 *
 * @author cifren
 */
interface DefinitionBuilderInterface
{
    public function build();

    public function getDefinition();

    public function getBuildItem();
}
