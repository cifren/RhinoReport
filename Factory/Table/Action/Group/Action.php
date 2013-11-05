<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Group;

use Fuller\ReportBundle\Model\Table\ReportObject\Group;

/**
 *  Fuller\ReportBundle\Factory\Table\Action\Group\Action
 *
 */
abstract class Action implements ActionInterface
{

    protected $group;
    protected $options;

    public function setParameters(Group $group, array $arguments)
    {
        $this->group = $group; // Column index value

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

    public function getGroup()
    {
        throw new \Exception('did you forget to declare `getGroup()` in your Action Class ?');
    }

}
