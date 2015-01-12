<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

use Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\Action;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\RoundAction
 *
 */
class RoundAction extends Action
{

    public function setData()
    {
        if ($this->options['dataId'])
            $data = $this->rowData[$this->options['dataId']];
        elseif ($this->options['displayId']) {
            $columnArg = $this->rowObject->getColumn($this->options['displayId']);
            if ($columnArg == null || empty($columnArg)) {
                throw new \UnexpectedValueException('Error on column \'' . $this->options['displayId'] . '\'' . ' in \'' . $this->column->getDefinition()->getPath() . '\'');
            }
            $data = $columnArg->getData();
        } else
            $data = $this->column->getData();

        $data = round((float) $data, $this->options['precision'], $this->options['mode']);

        return $data;
    }

    public function getOptions()
    {
        return array(
            'dataId' => null,
            'displayId' => null,
            'precision' => 2,
            'mode' => PHP_ROUND_HALF_UP,
        );
    }

}
