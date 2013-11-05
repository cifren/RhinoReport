<?php

namespace Fuller\ReportBundle\Definition\Table;

use Fuller\ReportBundle\Templating\ExportConfigurator\ExportConfigurator;
use \Fuller\ReportBundle\Util\Table\DataObject;

/**
 * Fuller\ReportBundle\Definition\Table\Definition
 *
 */
abstract class Definition
{

    protected $parent;
    protected $path;
    protected $exportConfig;
    protected $data;
    protected $attributes = array();

    public function __construct(array $exportConfigs)
    {
        foreach ($exportConfigs as &$class) {
            $class = new $class();
        }
        $this->setExportConfigs($exportConfigs);
    }

    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }
        return $this->parent->getPath() . '\\' . $this->excludeSpecialCharacter($this->id);
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

    public function end()
    {
        return $this->parent;
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
        if (!$type) {
            return $this->exportConfig;
        }
        if ($type && isset($this->exportConfig[$type])) {
            return $this->exportConfig[$type];
        } else {
            throw new \Exception('This export `' . $type . '´ is not available');
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

}
