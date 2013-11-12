<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Factory\Table\Action\Column\Action;

/*
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\DateFormatAction
 *
 */

class DateFormatAction extends Action
{

    public function setData()
    {
        if ($this->options['dataId'])
            $data = $this->rowData[$this->options['dataId']];
        elseif ($this->options['displayId'])
            $data = $this->rowObject->getColumn($this->options['displayId'])->getData();
        else
            $data = $this->column->getData();

        return $data->format($this->options['format']);
    }

    public function getOptions()
    {
        return array(
            'dataId' => null,
            'displayId' => null,
            'format' => 'm/d'
        );
    }

}
