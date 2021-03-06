<?php

namespace Earls\RhinoReportBundle\Report\ReportObject;

/**
 * Earls\RhinoReportBundle\Report\ReportObject\Report.
 */
class Report
{
    protected $items = array();

    /**
     * @var Filter
     */
    protected $filter;
    protected $options;

    public function getItems()
    {
        return $this->items;
    }

    public function getItem($id)
    {
        if ('filter' == $id) {
            return $this->getFilter();
        }

        return $this->items[$id];
    }

    public function addItem($item)
    {
        if ($item->getId()) {
            $this->items[$item->getId()] = $item;
        } else {
            $this->items[] = $item;
        }

        return $this;
    }

    /**
     * Get an array.
     *
     * @param type $fullColumn      Fill up column array if column doesn't exist in object
     * @param type $mergeColumnName Remove first line column name and change column id for next line with first row values
     *
     * @return array
     */
    public function getArray()
    {
        $arrayIterator = array();
        foreach ($this->items as $item) {
            $arrayIterator = array_merge($arrayIterator, $item->getArray(true, true, true, true));
        }

        return $arrayIterator;
    }

    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }
}
