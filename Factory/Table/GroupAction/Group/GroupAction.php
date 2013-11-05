<?php

namespace Fuller\ReportBundle\Factory\Table\GroupAction\Group;

use Fuller\ReportBundle\Model\Table\ReportObject\Table;
use Fuller\ReportBundle\Model\Table\ReportObject\Group;

/**
 *  Fuller\ReportBundle\Factory\Table\GroupAction\Group\GroupAction
 *
 */
abstract class GroupAction implements GroupActionInterface
{

    protected $group;
    protected $options;
    protected $dependences = array();

    public function setParameters(Group $group, Table $table, array $arguments)
    {
        $this->group = $group; // Column index value
        $this->table = $table; // Array of the raw row data

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

    public function setGroup()
    {
        throw new \Exception('did you forget to declare `getGroup()` in your groupAction Class ?');
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
