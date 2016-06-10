<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

/*
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\DateAddAction
 *
 */

class DateAddAction extends Action
{
    public function setData()
    {
        if ($this->options['dataId']) {
            $data = clone $this->rowData[$this->options['dataId']];
        } elseif ($this->options['displayId']) {
            $data = clone $this->rowObject->getColumn($this->options['displayId'])->getData();
        } else {
            $data = clone $this->column->getData();
        }

        return date_add($data, date_interval_create_from_date_string($this->options['interval']));
    }

    public function getOptions()
    {
        return array(
            'dataId' => null,
            'displayId' => null,
            'interval' => '',
        );
    }
}
