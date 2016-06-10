<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

/*
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\SprintfAction
 *
 */

class SprintfAction extends Action
{
    public function setData()
    {
        $data = array();

        foreach ($this->options['arg_dataIds'] as $columnName) {
            $data[] = $this->rowData[$columnName];
        }

        foreach ($this->options['arg_displayIds'] as $columnName) {
            $columnArg = $this->rowObject->getColumn($columnName);
            if ($columnArg != null || !empty($columnArg)) {
                $data[] = $columnArg->getData();
            } else {
                throw new \UnexpectedValueException('Error on column \''.$columnName.'\''.' in \''.$this->column->getDefinition()->getPath().'\'');
            }
        }

        $data = vsprintf($this->options['format'], $data);

        return $data;
    }

    public function getOptions()
    {
        return array(
            'arg_dataIds' => array(),
            'arg_displayIds' => array(),
            'format' => null,
        );
    }
}
