<?php

namespace Earls\RhinoReportBundle\Module\Table\Util;

/**
 * Earls\RhinoReportBundle\Module\Table\Util\xlsConditionalFormattingBloc
 */
class xlsConditionalFormattingBloc
{

    protected $range;
    protected $condition = array('value' => null, 'format' => array('style' => null));

    public function __construct($range, $condition, $style)
    {
        $this->setRange($range);

        $this->condition['value'] = $condition;
        $this->condition['format'] = $style;
    }

    public function getRange()
    {
        return $this->range;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function setRange($range)
    {
        $this->range = $range;
        return $this;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
    }

}
