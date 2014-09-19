<?php

namespace Earls\RhinoReportBundle\Model\Table\ReportObject;

use \Earls\RhinoReportBundle\Model\Table\ReportObject\Column;
use Earls\RhinoReportBundle\Model\Table\ReportObject\TableObject;
use Earls\RhinoReportBundle\Util\Table\DataObjectInterface;

/**
 * Earls\RhinoReportBundle\Model\Table\ReportObject\Row
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
            throw new \Exception(sprintf('The column with displayId \'%1$s\' doesn\'t exist', $displayId));
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
