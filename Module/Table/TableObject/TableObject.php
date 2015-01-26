<?php

namespace Earls\RhinoReportBundle\Module\Table\TableObject;

use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;

/*
 * Earls\RhinoReportBundle\Module\Table\TableObject\TableObject
 */

abstract class TableObject extends ModuleObject
{

    protected $data;
    protected $type = null;
    protected $parent;
    protected $parentPath;
    protected $partialPath;
    protected $fullPath;
    protected $configExport;
    protected $attributes = array();

    public function __construct($id, $definition, $parent = null)
    {
        $this->id = $id;
        $this->definition = $definition;
        $this->parent = $parent;
    }

    public function getPartialPath()
    {
        if (!$this->partialPath) {
            $this->partialPath = $this->type . ':%:' . $this->excludeSpecialCharacter($this->id);
        }

        return $this->partialPath;
    }

    public function getFullPath()
    {
        if (!$this->fullPath) {
            $this->fullPath = $this->getParentPath() . '\\' . $this->getPartialPath();
        }

        return $this->fullPath;
    }

    public function setParent(TableObject $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getParentPath()
    {
        return $this->parent->getFullPath();
    }

    public function getDataObject()
    {
        return $this->data;
    }

    public function setDataObject(DataObjectInterface $data)
    {
        $this->data = $data;

        return $this;
    }

    public function setAttribute($attr, $value)
    {
        if ($attr == 'class' && !is_array($value)) {
            throw new \Exception('Attribute `class´ for `' . get_class($this) . '´ `' . $this->getDefinition()->getPath() . '´ should be an array');
        }
        $this->attributes[$attr] = $value;

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

    public function getAttribute($attr)
    {
        if (isset($this->attributes[$attr])) {
            return $this->attributes[$attr];
        } else {
            return false;
        }
    }

    protected function excludeSpecialCharacter($string)
    {
        return str_replace(array('\\', ':%:', ':@:'), '', $string);
    }

    public function getType()
    {
        return 'table';
    }

}
