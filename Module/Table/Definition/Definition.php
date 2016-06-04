<?php

namespace Earls\RhinoReportBundle\Module\Table\Definition;

use Earls\RhinoReportBundle\Templating\ExportConfigurator\ExportConfigurator;
use Earls\RhinoReportBundle\Module\Table\Util\DataObject;
use Earls\RhinoReportBundle\Report\Definition\ModuleDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Definition\Definition
 */
abstract class Definition
{

    protected $displayId;
    protected $parent;
    protected $path;
    protected $exportConfig;
    protected $data;
    protected $attributes = array();

    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }
        
        return $this->getParent()->getPath() . '\\' . $this->excludeSpecialCharacter($this->getDisplayId());
    }

    public function setData(DataObject $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }
    
    public function getParent()
    {
        return $this->parent;
    }

    public function end()
    {
        return $this->getParent();
    }

    public function setExportConfig($type, ExportConfigurator $config)
    {
        $this->exportConfig[strtolower($type)] = $config;

        return $this;
    }

    public function setExportConfigs(array $configs)
    {
        foreach ($configs as $type => $config) {
            $this->setExportConfig($type, $config);
        }

        return $this;
    }

    public function getExportConfig($type = null)
    {
        $type = strtolower($type);
        if (!$type || !isset($this->exportConfig[$type])) {
            return null;
        } else {
            return $this->exportConfig[$type];
        }
    }

    public function setAttribute($attr, $value)
    {
        if ($attr == 'class' && !is_array($value)) {
            throw new \Exception('Attribute `class´ for `' . get_class($this) . '´ `' . $this->getDefinition()->getPath() . '´ should be an array');
        }
        //only for class => merge
        if (isset($this->attributes['class']) && $attr == 'class') {
            if (!in_array($value, $this->attributes['class'])) {
                $this->attributes['class'] = array_merge($this->attributes['class'], $value);
            }
        } else {
            $this->attributes[$attr] = $value;
        }

        return $this;
    }

    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $attr => $value) {
            $this->setAttribute($attr, $value);
        }

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    protected function excludeSpecialCharacter($string)
    {
        return str_replace(array('\\', ':%:', ':@:'), '', $string);
    }

    public function getDisplayId()
    {
        return $this->displayId;
    }

    public function setDisplayId($displayId)
    {
        $this->displayId = trim($displayId);
        return $this;
    }
}
