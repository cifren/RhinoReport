<?php

namespace Earls\RhinoReportBundle\Report\ReportObject;

/**
 * Description of Filter
 *
 * @author cifren
 */
class Filter
{

    protected $form;
    protected $availableExport;

    public function getForm()
    {
        return $this->form;
    }

    public function getAvailableExport()
    {
        return $this->availableExport;
    }

    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    public function setAvailableExport($availableExport)
    {
        $this->availableExport = $availableExport;
        return $this;
    }

}
