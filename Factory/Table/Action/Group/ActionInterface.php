<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Group;

use Earls\RhinoReportBundle\Model\Table\ReportObject\Group;

/**
 *  Earls\RhinoReportBundle\Factory\Table\Action\Group\ActionInterface
 *
 */
interface ActionInterface
{

    public function setParameters(Group $group, array $arguments);

    public function setOptions(array $arguments);

    public function getOptions();

    public function getGroup();
}
