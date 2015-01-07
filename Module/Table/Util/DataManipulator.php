<?php

namespace Earls\RhinoReportBundle\Module\Table\Util;

/**
 * Earls\RhinoReportBundle\Module\Table\Util\DataManipulator
 */
class DataManipulator implements DataManipulatorInterface
{
    /**
     * Get an array of all data seperate by filter
     *
     * @param \Earls\RhinoReportBundle\Module\Table\Util\DataObject $data
     * @param string                                         $groupByIndex
     *
     * @return \Earls\RhinoReportBundle\Module\Table\Util\DataObject
     */
    public function getArrayGroupBy(DataObject $data, $groupByIndex)
    {
        $filteredValues = $this->getArrayUniqueValuesFromGroupBy($data, $groupByIndex);

        $arrayGroupBy = array();
        foreach ($filteredValues as $value) {
            $arrayGroupBy[] = $this->getValueFilterByGroupBy($data, $groupByIndex, $value);
        }

        return $arrayGroupBy;
    }

    /**
     * Get unique values from DataObject with index
     *
     * @param DataObject $data
     * @param string     $index
     *
     * @return array of string
     */
    public function getArrayUniqueValuesFromGroupBy(DataObject $data, $index)
    {
        //take value for group by
        $func = function($v) use ($index) {
                    if (!array_key_exists($index, $v)) {
                        throw new \Exception('This column `'.$index.'` does\'nt exist in group of data given, check your sql/data or your groupings. The columns available to you are : '.implode(', ', array_keys($v)));
                    }

                    return $v[$index];
                };

        $uniqueValuesGroupBy = array_map($func, $data->getData());
        $uniqueValuesGroupBy = array_unique($uniqueValuesGroupBy);

        return $uniqueValuesGroupBy;
    }

    /**
     * Get DataObject filter by $filter Value on $index
     *
     * @param DataObject $data
     * @param string     $index
     * @param string     $filter
     *
     * @return \Earls\RhinoReportBundle\Module\Table\Util
     */
    public function getValueFilterByGroupBy(DataObject $data, $index, $filter)
    {
        $dataAry = $data->getData();

        //take value for group by
        $func = function($v) use ($index, $filter) {
                    return $v[$index] == $filter;
                };

        //apply filter for this value
        $dataAry = array_filter($dataAry, $func);

        $data = new DataObject($dataAry);

        return $data;
    }

    /**
     * Select the data from the id, if id doesn't exist return $dataObj untouched
     *
     * @param \Earls\RhinoReportBundle\Module\Table\Util\DataObject $dataObj
     * @param type                                           $id
     *
     * @return DataObject
     */
    public function selectData(DataObject $dataObj, $id)
    {
        $data = $dataObj->getData();
        if (isset($data[$id])) {
            $dataClone = clone $dataObj;

            return $dataClone->setData($data[$id]);
        } else {
            return $dataObj;
        }
    }
}
