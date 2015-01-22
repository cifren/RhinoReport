<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Description of DefinitionBuilderInterface
 *
 * @author cifren
 */
interface DefinitionBuilderInterface
{

    public function setParent(DefinitionBuilderInterface $parent);

    public function getParent();

    public function build();

    public function getDefinition();

    public function setId($id);

    public function getId();
}
