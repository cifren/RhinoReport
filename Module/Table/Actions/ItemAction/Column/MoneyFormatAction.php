<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\MoneyFormatAction.
 */
class MoneyFormatAction extends Action
{
    public function setData()
    {
        if ($this->options['dataId']) {
            $data = $this->rowData[$this->options['dataId']];
        } elseif ($this->options['displayId']) {
            $data = $this->rowObject->getColumn($this->options['displayId'])->getData();
        } else {
            $data = $this->column->getData();
        }

        $minus = false;
        if ($data < 0) {
            $data = $data * -1;
            $minus = true;
        }
        $data = '$'.number_format($data, 2, '.', ',');

        if ($minus) {
            $data = '-'.$data;
        }

        return $data;
    }

    public function getOptions()
    {
        return array(
            'dataId' => array(),
            'displayId' => array(),
            'format' => null,
        );
    }
}
