<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Group;

use Fuller\ReportBundle\Model\Table\ReportObject\Group;

/**
 *  Fuller\ReportBundle\Factory\Table\Action\Group\ActionInterface
 *
 */
interface ActionInterface
{

    public function setParameters(Group $group, array $arguments);

    public function setOptions(array $arguments);

    public function getOptions();

    public function getGroup();
}
