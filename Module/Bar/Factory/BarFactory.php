<?php

namespace Earls\RhinoReportBundle\Module\Bar\Factory;

use Earls\RhinoReportBundle\Module\Table\Util\DataManipulator;
use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;
use Earls\RhinoReportBundle\Report\Factory\Factory;
use Earls\RhinoReportBundle\Module\Bar\BarObject\Bar;
use Earls\RhinoReportBundle\Module\Bar\BarObject\Dataset;

/**
 * Description of BarFactory
 *
 * @author cifren
 */
class BarFactory extends Factory
{
    protected $dataManipulator;
    
    public function build()
    {
        $item = $this->createBarObject();

        $this->setItem($item);

        return $this;
    }

    protected function createBarObject()
    {
        $barDefinition = $this->getDefinition();
        $data = $this->getData();
        $barObject = new Bar();

        $barObject->setId($barDefinition->getId());
        $barObject->setLabels($this->getLabelsFromData($barDefinition->getLabelColumn(), $data));
        $transformedData = $this->getTransformedData($barObject->getLabels(), $barDefinition->getLabelColumn(), $barDefinition->getDataColumns(), $data);
        $datasets = array();
        foreach($barDefinition->getDataColumns() as $columnName => $columnlabel){
            $dataset = new Dataset($columnlabel, $transformedData[$columnName]);
            $datasets[] = $dataset;
        }
        $barObject->setDatasets($datasets);

        return $barObject;
    }

    protected function getTransformedData(array $labels, $labelColumnName, array $columnNames, DataObjectInterface $dataObj)
    {
        $transformedData = array();
        $data = $dataObj->getData();
        
        foreach ($labels as $label) {
            $callback = function($var) use ($label, $labelColumnName) {
                return $var[$labelColumnName] == $label;
            };
            $labelData = array_filter($data, $callback);
            
            foreach ($columnNames as $columnName => $columnlabel) {
                $sumColumnValues = null;
                foreach ($labelData as $row) {
                    $sumColumnValues += $row[$columnName];
                }
                $transformedData[$columnName][] = $sumColumnValues;
            }
        }
        
        return $transformedData;
    }

    protected function sumUpData(array $data)
    {

        return $data;
    }

    protected function getLabelsFromData($columnName, DataObjectInterface $data)
    {
        $labels = $this->getDataManipulator()->getArrayUniqueValuesFromGroupBy($data, $columnName);

        return $labels;
    }
    
    protected function getDataManipulator(){
        if(!$this->dataManipulator){
            return $this->dataManipulator = new DataManipulator();
        }
        
        return $this->dataManipulator;
    }

}
