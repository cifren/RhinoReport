<?php

namespace Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Model;

/**
 * Description of ModuleTemplate
 *
 * @author cifren
 */
class ModuleTemplate
{

    protected $templatingName;
    protected $moduleObject;
    protected $remoteUrl;
    protected $exportUrl;
    protected $options;
    protected $data;

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

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getTemplatingName()
    {
        return $this->templatingName;
    }

    public function setTemplatingName($templatingName)
    {
        $this->templatingName = $templatingName;
        return $this;
    }

}
