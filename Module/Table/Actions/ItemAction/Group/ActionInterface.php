<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Group;

use Earls\RhinoReportBundle\Module\Table\TableObject\Group;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Group\ActionInterface.
 */
interface ActionInterface
{
    public function setParameters(Group $group, array $arguments);

    public function setOptions(array $arguments);

    public function getOptions();

    public function getGroup();
}
