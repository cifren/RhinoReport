<?php

namespace Earls\RhinoReportBundle\Module\Bar\Factory;

use Earls\RhinoReportBundle\Report\Factory\Factory;
use Earls\RhinoReportBundle\Module\Bar\BarObject\BarObject;

/**
 * Description of BarFactory
 *
 * @author cifren
 */
class BarFactory extends Factory
{

    public function build()
    {
        $item = $this->createBarObject();

        $this->setItem($item);

        return $this;
    }
    
    protected function createBarObject(){
        return new BarObject();
    }

}
