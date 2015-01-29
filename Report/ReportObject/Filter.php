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
    protected $options;

    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

}
