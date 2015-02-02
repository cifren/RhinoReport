<?php

namespace Earls\RhinoReportBundle\Module\Bar\BarObject;

use Doctrine\Common\Collections\ArrayCollection;
use Earls\RhinoReportBundle\Report\ReportObject\ModuleObject;
use Earls\RhinoReportBundle\Module\Bar\BarObject\Dataset;

/**
 * Description of BarObject
 *
 * @author cifren
 */
class Bar extends ModuleObject
{

    protected $labels = array();
    protected $datasets;

    public function __construct()
    {
        $this->datasets = new ArrayCollection();
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;
        return $this;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function getDatasets()
    {
        return $this->datasets;
    }

    public function addDataset(Dataset $dataset)
    {
        $this->datasets->add($dataset);

        return $this;
    }

    public function setDatasets(array $datasets)
    {
        foreach ($datasets as $dataset) {
            $this->addDataset($dataset);
        }

        return $this;
    }

    public function getType()
    {
        return 'bar';
    }

}
