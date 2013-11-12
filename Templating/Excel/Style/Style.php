<?php

namespace Earls\RhinoReportBundle\Templating\Excel\Style;

/**
 * Earls\RhinoReportBundle\Templating\Excel\Style\Style
 */
class Style
{

    protected $parent;
    protected $font = array();
    protected $alignment = array();
    protected $topBorder = array();
    protected $rightBorder = array();
    protected $leftBorder = array();
    protected $bottomBorder = array();
    protected $interior = array();
    protected $numberFormat = array();
    protected $protection = array();

    public function addNewRule($type, $name, $value)
    {
        $this->{'add'.ucfirst($type).'Option'}($name, $value);

        return $this;
    }

    public function getAllStyle()
    {
        return array(
            'Font' => $this->getFont(),
            'Alignment' => $this->getAlignment(),
            'Borders' => $this->getBorders(),
            'Interior' => $this->getInterior(),
            'NumberFormat' => $this->getNumberFormat(),
            'Protection' => $this->getProtection(),
        );
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getFont()
    {
        return $this->font;
    }

    public function addFontOption($name, $value)
    {
        return $this->font[$name] = $value;
    }

    public function getAlignment()
    {
        return $this->alignment;
    }

    public function addAlignmentOption($name, $value)
    {
        return $this->alignment[$name] = $value;
    }

    public function getInterior()
    {
        return $this->interior;
    }

    public function addInteriorOption($name, $value)
    {
        return $this->interior[$name] = $value;
    }

    public function getNumberFormat()
    {
        return $this->numberFormat;
    }

    public function addNumberFormatOption($name, $value)
    {
        return $this->numberFormat[$name] = $value;
    }

    public function getProtection()
    {
        return $this->protection;
    }

    public function addProtectionOption($name, $value)
    {
        return $this->protection[$name] = $value;
    }

    public function getBorders()
    {
        if(!empty($this->topBorder) && !empty($this->rightBorder) && !empty($this->bottomBorder) && !empty($this->leftBorder))

            return array('top' => $this->topBorder, 'right' => $this->rightBorder, 'bottom' => $this->bottomBorder, 'left' => $this->leftBorder);
        else
            return array();
    }

    public function addTopBorderOption($name, $value)
    {
        if (empty($this->topBorder)) {
            $this->topBorder['ss:Position'] = 'Top';
        }

        return $this->topBorder[$name] = $value;
    }

    public function addRightBorderOption($name, $value)
    {
        if (empty($this->rightBorder)) {
            $this->rightBorder['ss:Position'] = 'Right';
        }

        return $this->rightBorder[$name] = $value;
    }

    public function addLeftBorderOption($name, $value)
    {
        if (empty($this->leftBorder)) {
            $this->leftBorder['ss:Position'] = 'Left';
        }

        return $this->leftBorder[$name] = $value;
    }

    public function addBottomBorderOption($name, $value)
    {
        if (empty($this->bottomBorder)) {
            $this->bottomBorder['ss:Position'] = 'Bottom';
        }

        return $this->bottomBorder[$name] = $value;
    }

}
