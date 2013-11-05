<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Row;

use Fuller\ReportBundle\Factory\Table\Action\Row\Action;

/*
 *  Fuller\ReportBundle\Factory\Table\Action\Row\HighlightMinMaxAction
 *
 */

class HighlightMinMaxAction extends Action
{
    protected $min_max = array('max', 'min');

    public function __construct($maxMinValue)
    {
        if (!in_array($maxMinValue, $this->min_max)) {
            throw new \UnexpectedValueException('Error on \'HighlightMinMaxRowAction\' in \''.$this->row->getDefinition()->getPath().'\' MIN/MAX value can be only \'max\' or \'min\'');
        }
        $this->minMaxValue = $maxMinValue;
    }

    public function setRow()
    {
        if (!$this->options['displayIds'] || count($this->options['displayIds']) < 2) {
            throw new \UnexpectedValueException('Error on \'HighlightMinMaxRowAction\' in \''.$this->row->getDefinition()->getPath().'\', value \'displayIds\' can\'t be empty or inf 2 values');
        }

        $aryValues = array();
        //stock all values
        foreach ($this->options['displayIds'] as $displayId) {
            $column = $this->row->getColumn($displayId);
            //if column null
            if (!$column) {
                throw new \UnexpectedValueException("Error on 'HighlightMinMaxRowAction' in '".$this->row->getDefinition()->getPath()."', displayId '$displayId' doesn't exist or an error happened");
            }
            $aryValues[$displayId] = floatval($column->getData());
        }

        //max or min value
        $mValue = $this->mFunction($aryValues);

        //pull displayIds with value min or max
        $mDisplayIds = array();
        foreach ($aryValues as $displayId => $value) {
            if ($value == $mValue) {
                $mDisplayIds[] = $displayId;
            }
        }

        //add new class to column
        foreach ($mDisplayIds as $displayId) {
            //merge existing and new class
            $this->row->getColumn($displayId)->setAttribute('class', is_array($column->getAttribute('class'))?array_merge($column->getAttribute('class'), $this->options['class']):$this->options['class']);
        }

        return $this->row;
    }

    private function mFunction(array $values)
    {
        if ($this->minMaxValue == "max") {
            return $m = max($values);
        } elseif ($this->minMaxValue == "min") {
            return $m = min($values);
        }
    }

    public function getOptions()
    {
        $defaultClass = 'hl-'.$this->minMaxValue;

        return array(
            'displayIds' => array(),
            'class' => $defaultClass
        );
    }

}
