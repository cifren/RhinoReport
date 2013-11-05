<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Column;

use Fuller\ReportBundle\Factory\Table\Action\Column\Action;

/*
 *  Fuller\ReportBundle\Factory\Table\Action\Column\CalculationAction
 *
 */

class CalculationAction extends Action
{

    public function setData()
    {

        $data = array();

        foreach ($this->options['arg_displayIds'] as $columnName) {
            $columnArg = $this->rowObject->getColumn($columnName);
            if ($columnArg == null || empty($columnArg)) {
                 throw new \UnexpectedValueException('Error on column \''.$columnName.'\''.' in \''.$this->column->getDefinition()->getPath().'\'');
            }

            if ($columnArg->getData() == null) {
                $data[] = 0;

            } else {
                $data[] = $columnArg->getData();
            }
        }

        $formula = vsprintf($this->options['formula'], $data);

        $total = null;

        eval('$total = ' . $formula . ';');

        return $total;
    }

    public function getOptions()
    {
        return array(
            'arg_dataIds' => array(),
            'arg_displayIds' => array(),
            'operator' => null
        );
    }

}
