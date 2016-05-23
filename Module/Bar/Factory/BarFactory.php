<?php

namespace Earls\RhinoReportBundle\Module\Bar\Factory;

use Doctrine\Common\Collections\Collection;
use Earls\RhinoReportBundle\Module\Table\Util\DataManipulator;
use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;
use Earls\RhinoReportBundle\Report\Factory\AbstractFactory;
use Earls\RhinoReportBundle\Module\Bar\BarObject\Bar;
use Earls\RhinoReportBundle\Module\Bar\BarObject\Dataset;

/**
 * Description of BarFactory
 *
 * @author cifren
 */
class BarFactory extends AbstractFactory
{

    protected $dataManipulator;

    public function build()
    {
        $this->dataManipulator = null;
        $item = $this->createBarObject();

        $this->setItem($item);

        return $this;
    }

    protected function createBarObject()
    {
        $barDefinition = $this->getDefinition();
        $data = $this->getData();
        $barObject = new Bar();

        $barObject->setDefinition($barDefinition);
        $barObject->setId($barDefinition->getDisplayId());
        $barObject->setLabels($this->getLabelsFromData($barDefinition->getLabelColumn(), $data));
        $transformedData = $this->getTransformedData($barObject->getLabels(), $barDefinition->getLabelColumn(), $barDefinition->getDatasets(), $data);
        $datasets = array();
        foreach ($barDefinition->getDatasets() as $key => $datasetDef) {
            if($datasetDef->getOptions()){
                $options = array_merge($this->getDefaultOptionsDatasetColors()[$key], $datasetDef->getOptions()->toArray());
            }
            if(!empty($transformedData)){
                $dataset = new Dataset($datasetDef->getLabelColumn(), $transformedData[$datasetDef->getDataColumn()], $options);
                $datasets[] = $dataset;
            }
        }
        $barObject->setDatasets($datasets);

        return $barObject;
    }

    protected function getTransformedData(array $labels, $labelColumnName, Collection $datasets, DataObjectInterface $dataObj)
    {
        $transformedData = array();
        $data = $dataObj->getData();

        foreach ($labels as $label) {
            $callback = function($var) use ($label, $labelColumnName) {
                return $var[$labelColumnName] == $label;
            };
            $labelData = array_filter($data, $callback);

            foreach ($datasets as $dataset) {
                $columnName = $dataset->getDataColumn();
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

    protected function getDataManipulator()
    {
        if (!$this->dataManipulator) {
            return $this->dataManipulator = new DataManipulator();
        }

        return $this->dataManipulator;
    }

    protected function getDefaultOptionsDatasetColors()
    {
        $colors = array(
            array(
                'fillColor' => '#da5859',
                'strokeColor' => '#CF9898',
                'highlightFill' => '#D34444',
                'highlightStroke' => '#da5859',
            ),
            array(
                'fillColor' => '#a5bdd7',
                'strokeColor' => '#C4CAD0',
                'highlightFill' => '#4E90D6',
                'highlightStroke' => '#a5bdd7',
            ),
            array(
                'fillColor' => '#a5d7d0',
                'strokeColor' => '#C4D9D6',
                'highlightFill' => '#42D9C2',
                'highlightStroke' => '#a5d7d0',
            ),
            array(
                'fillColor' => '#fbc987',
                'strokeColor' => '#FEE4C2',
                'highlightFill' => '#FBA332',
                'highlightStroke' => '#fbc987',
            ),
            array(
                'fillColor' => '#f09777',
                'strokeColor' => '#EFB9A5',
                'highlightFill' => '#EF602C',
                'highlightStroke' => '#f09777',
            ),
            array(
                'fillColor' => '#da5859',
                'strokeColor' => '#DA8484',
                'highlightFill' => '#DA2626',
                'highlightStroke' => '#da5859',
            ),
        );
        return $colors;
    }

}
