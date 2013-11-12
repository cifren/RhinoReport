<?php

namespace Earls\RhinoReportBundle\Factory\Table\GroupAction\Column;

use Earls\RhinoReportBundle\Model\Table\ReportObject\Table;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Column;

/**
 *  Earls\RhinoReportBundle\Factory\Table\GroupAction\Column\GroupActionInterface
 *
 */
interface GroupActionInterface
{

    public function setParameters(Column $column, Table $table, array $arguments);

    public function setOptions(array $arguments);

    public function getOptions();

    public function setData();

    public function getDependences();
}
