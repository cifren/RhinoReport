<?php

namespace Earls\RhinoReportBundle\Report\Templating\Model;

/**
 * Description of ModuleTemplate
 *
 * @author cifren
 */
class ModuleTemplate
{

    protected $moduleObject;
    protected $remoteUrl;
    protected $exportUrl;

    public function getModuleObject()
    {
        return $this->moduleObject;
    }

    public function getRemoteUrl()
    {
        return $this->remoteUrl;
    }

    public function getExportUrl()
    {
        return $this->exportUrl;
    }

    public function setModuleObject($moduleObject)
    {
        $this->moduleObject = $moduleObject;
        return $this;
    }

    public function setRemoteUrl($remoteUrl)
    {
        $this->remoteUrl = $remoteUrl;
        return $this;
    }

    public function setExportUrl($exportUrl)
    {
        $this->exportUrl = $exportUrl;
        return $this;
    }

    public function getPosition()
    {
        return $this->getModuleObject()->getPosition();
    }

    public function getType()
    {
        return $this->getModuleObject()->getType();
    }

}
