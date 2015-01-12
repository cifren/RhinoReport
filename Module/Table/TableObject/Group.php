<?php

namespace Earls\RhinoReportBundle\Module\Table\TableObject;

use Earls\RhinoReportBundle\Module\Table\TableObject\TableObject;
use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;

/*
 * Earls\RhinoReportBundle\Module\Table\TableObject\Group
 */

class Group extends TableObject
{

    protected $items = array();
    protected $type;
    protected $genericId;
    protected $partialPath;
    protected $groupType;

    public function __construct($id, $genericId, $definition, $parent, DataObjectInterface $data)
    {
        $this->type = 'group';
        $this->genericId = $genericId;
        $this->setDataObject($data);

        parent::__construct($id, $definition, $parent);
    }

    public function getGenericId()
    {
        return $this->genericId;
    }

    public function setGenericId($genericId)
    {
        $this->genericId = $genericId;

        return $this;
    }

    public function getPartialPath()
    {
        if (!$this->partialPath) {
            $this->partialPath = $this->type . ':%:' . $this->excludeSpecialCharacter($this->genericId) . ':@:' . $this->excludeSpecialCharacter($this->id);
        }

        return $this->partialPath;
    }

    public function setGroupType($groupTypeName)
    {
        $this->groupType = $groupTypeName;

        return $this;
    }

    public function getGroupType()
    {
        return $this->groupType;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getItem($partialPath)
    {
        return $this->items[$partialPath];
    }

    public function setItems(array $items)
    {
        $this->items = null;

        $this->addItems($items);

        return $this;
    }

    public function addItem($item)
    {
        return $this->items[$item->getPartialPath()] = $item;
    }

    public function addItems(array $items)
    {
        foreach ($items as $i) {
            $this->addItem($i);
        }

        return $this->items;
    }

    public function getRowSpans()
    {
        return $this->getDefinition()->getRowSpans();
    }

}
