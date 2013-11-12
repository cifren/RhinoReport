<?php

namespace Earls\RhinoReportBundle\Factory\Table\GroupAction\Group;

use Earls\RhinoReportBundle\Model\Table\ReportObject\Group;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Table;

/**
 *  Earls\RhinoReportBundle\Factory\Table\GroupAction\Group\GroupActionInterface
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
