<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Column;

use Fuller\ReportBundle\Factory\Table\Action\Column\Action;

/*
 *  Fuller\ReportBundle\Factory\Table\Action\Column\LabelFieldAction
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
