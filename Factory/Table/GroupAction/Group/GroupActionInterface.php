<?php

namespace Fuller\ReportBundle\Factory\Table\GroupAction\Group;

use Fuller\ReportBundle\Model\Table\ReportObject\Group;
use Fuller\ReportBundle\Model\Table\ReportObject\Table;

/**
 *  Fuller\ReportBundle\Factory\Table\GroupAction\Group\GroupActionInterface
 *
 */
interface GroupActionInterface
{

    public function setParameters(Group $group, Table $table, array $arguments);

    public function setOptions($arguments);

    public function getOptions();

    public function setGroup();

    public function getDependences();
}
