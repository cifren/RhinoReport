<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

/*
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\LabelFieldAction
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
            'label' => null,
        );
    }
}
