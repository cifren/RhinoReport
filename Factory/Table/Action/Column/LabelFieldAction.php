<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Factory\Table\Action\Column\Action;

/*
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\LabelFieldAction
 *
 */
class LabelFieldAction extends Action
{

    public function setData()
    {
        $data = $this->options['label'];

        return $data;
    }

    public function getOptions()
    {
        return array(
            'label' => null
        );
    }

}
