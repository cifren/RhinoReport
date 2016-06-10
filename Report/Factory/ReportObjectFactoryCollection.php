<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Earls\RhinoReportBundle\Report\Factory\ReportObjectFactoryCollection.
 *
 * Description of ReportObjectFactoryCollection
 *
 * @author cifren
 */
class ReportObjectFactoryCollection
{
    protected $factories;

    public function __construct()
    {
        $this->factories = new ArrayCollection();
    }

    public function addFactory($type, $factory)
    {
        if (key_exists($type, $factories)) {
            throw new \Exception("This type '$type' already exists");
        }
        $this->factories[$type] = $factory;

        return $this;
    }

    public function setFactory($type, $factory)
    {
        $this->factories[$type] = $factory;

        return $this;
    }

    public function setFactories($factories)
    {
        $this->factories = $factories;

        return $this;
    }

    public function getFactories()
    {
        return $factories;
    }

    public function getFactory($type)
    {
        return $this->factories[$type];
    }
}
