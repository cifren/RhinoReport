<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\CustomPhpAction.
 *
 * This class will call a custom function you want invoke
 *
 * this is an exmaple how to use it
 *      ->action('custom_php', array('functionName' => 'number_format', 'arguments' => array('$data', '2', '\'.\'', '\'\'')))
 * custom_php is action name
 * functionName is string, function name you want to call
 * arguments is array, each argument will be apply to the function name, $data is variable of the column dataId/displayId or itself if following are empty
 */
class CustomPhpAction extends Action
{
    public function setData()
    {
        if (!$this->options['functionName']) {
            throw new \UnexpectedValueException('Error on column \''.$this->options['displayId'].'\''.' in \''.$this->column->getDefinition()->getPath().'\', `functionName` can\'t be empty');
        }

        if ($this->options['dataId']) {
            $data = $this->rowData[$this->options['dataId']];
        } elseif ($this->options['displayId']) {
            $columnArg = $this->rowObject->getColumn($this->options['displayId']);
            if ($columnArg == null || empty($columnArg)) {
                throw new \UnexpectedValueException('Error on column \''.$this->options['displayId'].'\''.' in \''.$this->column->getDefinition()->getPath().'\'');
            }
            $data = $columnArg->getData();
        } else {
            $data = $this->column->getData();
        }

        $argQuery = implode(',', $this->options['arguments']);

        $evalQuery = '$data = '.$this->options['functionName']."($argQuery);";

        eval($evalQuery);

        return $data;
    }

    public function getOptions()
    {
        return array(
            'dataId' => null,
            'displayId' => null,
            'functionName' => null, //function name called
            'arguments' => array(),  // function arguments, $data is value you want modify
        );
    }
}
