<?php

namespace Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Template
 *
 * @author cifren
 */
class Template
{

    protected $modules;
    protected $uniqueModules;
    protected $filter;
    protected $remoteUrl;
    protected $exportUrl;
    protected $options;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->uniqueModules = new ArrayCollection();
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function addModule($module)
    {
        $this->modules->add($module);

        return $this;
    }

    public function setModules(array $modules)
    {
        foreach ($modules as $module) {
            $this->addModule($module);
        }

        return $this;
    }

    public function setUniqueModules($uniqueModules)
    {
        $this->uniqueModules = $uniqueModules;
        return $this;
    }

    public function addUniqueModule($type, $uniqueModule)
    {
        $this->uniqueModules[$type] = $uniqueModule;

        return $this;
    }

    public function getUniqueModule($type)
    {
        return $this->uniqueModules[$type];
    }

    public function getUniqueModules()
    {
        return $this->uniqueModules;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getModulesOnPostion($positionName)
    {
        $selectedModules = array();
        foreach ($this->getModules() as $module) {
            if ($module->getPosition() == $positionName) {
                $selectedModules[] = $module;
            }
        }

        return $selectedModules;
    }

    public function getType()
    {
        return "table";
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

    public function getRemoteUrl()
    {
        return $this->remoteUrl;
    }

    public function setRemoteUrl($remoteUrl)
    {
        $this->remoteUrl = $remoteUrl;
        return $this;
    }

}
