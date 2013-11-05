<?php

namespace Fuller\ReportBundle\Model\Table\ReportObject;

use Fuller\ReportBundle\Model\Table\ReportObject\TableObject;

/*
 * Fuller\ReportBundle\Model\Table\ReportObject\Column
 */

class Column extends TableObject
{

    protected $baseValue = null;
    protected $formatExcel = null;
    protected $id;
    protected $data;
    protected $formula;
    protected $colSpan = false;
    protected $position = null;

    public function __construct($id, $definition, $parent)
    {
        $this->type = 'column';

        parent::__construct($id, $definition, $parent);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setBaseValue($data)
    {
        $this->baseValue = $data;

        return $this;
    }

    public function getBaseValue()
    {
        return $this->baseValue;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setFormula($formula)
    {
        $this->formula = $formula;

        return $this;
    }

    public function getFormula()
    {
        return $this->formula;
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

    public function setFormatExcel($formatExcel)
    {
        $this->formatExcel = $formatExcel;

        return $this;
    }

    public function getFormatExcel()
    {
        return $this->formatExcel;
    }

    public function getRow()
    {
        return $this->getParent();
    }

    //position : 1, 2, 3...
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    //position : 1, 2, 3...
    public function getPosition()
    {
        return $this->position;
    }

    //position : R1C1, R1C2, R2C1...
    public function getFullPosition()
    {
        return 'R' . $this->getParent()->getPosition() . 'C' . $this->position;
    }

}
