<?php

namespace Earls\RhinoReportBundle\Module\Table\TableObject;

use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;

/**
 * Earls\RhinoReportBundle\Module\Table\TableObject\Row.
 */
class Row extends TableObject
{
    protected $columns = array();
    protected $rowSpans = array();
    protected $position = null;
    protected $type;

    public function __construct($id, $definition, $parent, DataObjectInterface $data)
    {
        $this->type = 'row';
        $this->setDataObject($data);

        parent::__construct($id, $definition, $parent);
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumn($displayId, Column $column)
    {
        $this->columns[$displayId] = $column;

        return $this;
    }

    public function getColumn($displayId)
    {
        if (!isset($this->columns[$displayId])) {
            return null;
        }

        return $this->columns[$displayId];
    }

    public function addColumn($displayId, Column $column)
    {
        $this->columns[$displayId] = $column;

        return $this->columns;
    }

    public function deleteColumn($displayId)
    {
        unset($this->columns[$displayId]);

        return $this;
    }

    public function hasTypeDefinition($type)
    {
        foreach ($this->getColumns() as $col) {
            if ($col->getDefinition()->getType() == $type) {
                return true;
            }
        }

        return false;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getGroup()
    {
        return $this->getParent();
    }
}
