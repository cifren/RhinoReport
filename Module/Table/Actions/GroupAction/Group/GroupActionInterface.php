<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Group;

use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\Table;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Group\GroupActionInterface
 *
 */
interface GroupActionInterface
{

    public function setParameters(Group $group, Table $table, array $arguments);

    public function setOptions(array $arguments);

    public function getOptions();

    public function setGroup();

    public function getDependences();
}
