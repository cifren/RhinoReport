<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column;

use Earls\RhinoReportBundle\Module\Table\TableObject\Table;
use Earls\RhinoReportBundle\Module\Table\TableObject\Column;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column\GroupActionInterface.
 */
interface GroupActionInterface
{
    public function setParameters(Column $column, Table $table, array $arguments);

    public function setOptions(array $arguments);

    public function getOptions();

    public function setData();

    public function getDependences();
}
