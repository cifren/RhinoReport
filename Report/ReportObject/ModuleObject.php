<?php

namespace Earls\RhinoReportBundle\Report\ReportObject;

/**
 * Description of ModuleObject
 *
 * @author cifren
 */
abstract class ModuleObject
{

    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}
