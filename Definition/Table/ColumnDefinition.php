<?php

namespace Fuller\ReportBundle\Definition\Table;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Fuller\ReportBundle\Definition\Table\RowDefinition;

/**
 *  Fuller\ReportBundle\Definition\Table\ColumnDefinition
 *
 */
class ColumnDefinition extends Definition
{

    const TYPE_DATA = 'data';
    const TYPE_DISPLAY = 'display';

    protected $parent;
    protected $displayId;
    protected $baseData = array();
    protected $formatExcel;
    protected $formulaExcel;
    protected $actions = array();
    protected $groupAction = null;
    protected $extendingGroupAction = false;
    protected $type; //type 'display' will be display on screen or type 'data' will be used for display and removed
    protected $order;

    public function __construct($displayId, $type, array $exportConfigs, $dataId = null)
    {
        parent::__construct($exportConfigs);

        $this->displayId = $displayId;
        $this->setAttribute('class', array('column_' . $displayId));
        $this->type = $type;
        if ($dataId) {
            $this->setBaseData('dataId', $dataId);
        }
    }

    public function getBaseData()
    {
        return $this->baseData;
    }

    public function setBaseData($type, $arg)
    {
        $this->baseData['type'] = $type;
        $this->baseData['column'] = $arg;

        return $this;
    }

    public function getDisplayId()
    {
        return $this->displayId;
    }

    public function getDataDisplayId()
    {
        return $this->displayId;
    }

    public function setDataDisplayId($dataDisplayId)
    {
        $this->dataDisplayId = $dataDisplayId;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setFormatExcel($formatExcel)
    {
        $this->formatExcel = $formatExcel;

        return $this;
    }

    public function getFormatExcel()
    {
        return $this->formatExcel;
    }

    public function setFormulaExcel($formulaExcel)
    {
        $this->formulaExcel = $formulaExcel;

        return $this;
    }

    public function getFormulaExcel()
    {
        return $this->formulaExcel;
    }

    public function setColSpan($nbCol)
    {
        $this->attributes['colspan'] = $nbCol;

        return $this;
    }

    public function getColSpan()
    {
        if (isset($this->attributes['colspan'])) {
            return $this->attributes['colspan'];
        } else {
            return false;
        }
    }

    public function setParent($parent)
    {
        if (!$parent instanceof RowDefinition) {
            throw new UnexpectedTypeException($this->parent, 'Fuller\ReportBundle\Definition\RowDefinition');
        }
        parent::setParent($parent);
    }

    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }
        return $this->path = $this->parent->getPath() . '.' . $this->excludeSpecialCharacter($this->displayId);
    }

    public function setGroupAction($name, array $arg, $dependences = array())
    {
        $this->groupAction = array(
            'name' => $name,
            'arg' => $arg,
            'dependences' => $dependences
        );
        //execute either extendingGroupAction or groupAction
        $this->extendingGroupAction = null;

        return $this;
    }

    public function getGroupAction()
    {
        return $this->groupAction;
    }

    public function hasGroupAction()
    {
        return $this->groupAction != null;
    }

    public function setExtendingGroupAction($dependences = array())
    {
        $this->extendingGroupAction = array(
            'dependences' => $dependences
        );
        //execute either extendingGroupAction either groupAction
        $this->groupAction = null;

        return $this;
    }

    public function getExtendingGroupAction()
    {
        return $this->extendingGroupAction;
    }

    public function hasExtendingGroupAction()
    {
        return $this->extendingGroupAction != null;
    }

    public function addAction($name, array $arg)
    {
        $this->actions[] = array(
            'name' => $name,
            'arg' => $arg
        );
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function hasActions()
    {
        return count($this->actions) > 0;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

}
