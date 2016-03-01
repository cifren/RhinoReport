<?php

namespace Earls\RhinoReportBundle\Report\Definition;

/**
 * Earls\RhinoReportBundle\Report\Definition\ModuleDefinitionInterface
 * 
 * @author cifren
 */
interface ModuleDefinitionInterface
{

    public function getDisplayId();

    public function setDisplayId($displayId);

    public function getParent();

    public function setParent($parent);

    public function getPosition();

    public function setPosition($position);

    public function getTemplate();

    public function setTemplate($template);
    
    public function setModuleType($type);
    
    public function getModuleType();
    
}
