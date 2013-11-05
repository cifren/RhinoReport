<?php

namespace Fuller\ReportBundle\Model;

use Symfony\Component\Form\Form;

/**
 * Fuller\ReportBundle\Model\Report
 */
class Report
{

    protected $items = array();
    protected $filter;
    protected $availableExport = array();

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
        if ($item->getId())
            $this->items[$item->getId()] = $item;
        else
            $this->items[] = $item;

        return $this;
    }

    /**
     * Get an array
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

    public function setFilter(Form $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getAvailableExport()
    {
        return $this->availableExport;
    }

    public function setAvailableExport(array $availableExport)
    {
        $this->availableExport = $availableExport;

        return $this;
    }

}
