<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Row;

use Fuller\ReportBundle\Model\Table\ReportObject\Row;

/**
 *  Fuller\ReportBundle\Factory\Table\Action\Row\Action
 *
 */
abstract class Action implements ActionInterface
{

    protected $row;
    protected $options;
    protected $dependences = array();

    public function setParameters(Row $row, array $arguments)
    {
        $this->row = clone $row; // Column index value

        $this->setOptions($arguments);

        return $this;
    }

    public function setOptions(array $arguments)
    {
        $this->options = $this->getOptions();
        $this->options = $arguments + $this->options;

        return $this;
    }

    public function getOptions()
    {
        return array();
    }

    public function setRow()
    {
        throw new \Exception('did you forget to declare `setRow()` in your Action Class ?');
    }

    public function getDependences()
    {
        return $this->dependences;
    }

    public function setDependences(array $dependences)
    {
        $this->dependences = $dependences;

        return $this;
    }

}
