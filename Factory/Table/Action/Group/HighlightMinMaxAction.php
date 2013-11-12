<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Group;

use Earls\RhinoReportBundle\Factory\Table\Action\Group\Action;
use Earls\RhinoReportBundle\Factory\TableRetriever;

/**
 *  Earls\RhinoReportBundle\Factory\Table\Action\Group\HighlightMinMaxAction
 *
 */
class HighlightMinMaxAction extends Action
{

    protected $retriever;
    protected $min_max = array('max', 'min');

    public function __construct(TableRetriever $retriever, $maxMinValue)
    {
        if (!in_array($maxMinValue, $this->min_max)) {
            throw new \UnexpectedValueException('Error on \'HighlightMinMaxRowAction\' in \'' . $this->group->getDefinition()->getPath() . '\' MIN/MAX value can be only \'max\' or \'min\'');
        }
        $this->minMaxValue = $maxMinValue;
        $this->retriever = $retriever;
    }

    public function setGroup()
    {
        if ($this->options['group']) {
            $subGroup = $this->retriever->getParentOrSubItemsFromGenericPath($this->options['group'], $this->group);
        } else {
            $subGroup = array($this->group);
        }
        $aryValues = array();
        $columnsAry = array();
        
        foreach ($subGroup as $group) {
            //for each column get all value
            foreach ($this->options['displayIds'] as $displayId) {
                $columns = $this->retriever->getColumns($displayId, $group);
                foreach ($columns as $column) {
                    $columnsAry[] = $column;
                    $aryValues[] = floatval($column->getData());
                }
            }
        }
        //no data
        if (!$aryValues) {
            return $this->group;
        }

        //compare and find max/min value
        $mValue = $this->mFunction($aryValues);

        foreach ($columnsAry as $column) {
            if ($mValue != 0 || $this->options['highlightZero'] != 0) {
                if (floatval($column->getData()) == $mValue) {
                    $column->setAttribute('class', is_array($column->getAttribute('class')) ? array_merge($column->getAttribute('class'), $this->options['class']) : $this->options['class']);
                }
            }
        }

        return $this->group;
    }

    protected function mFunction(array $values)
    {
        if ($this->minMaxValue == "max") {
            return $m = max($values);
        } elseif ($this->minMaxValue == "min") {
            return $m = min($values);
        }
    }

    public function getOptions()
    {
        $defaultClass = 'hl-group-' . $this->minMaxValue;
        return array(
            'group' => null,
            'displayIds' => array(),
            'class' => array($defaultClass),
            'highlightZero' => 0
        );
    }

}
