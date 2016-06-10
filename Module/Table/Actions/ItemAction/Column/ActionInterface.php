<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

use Earls\RhinoReportBundle\Module\Table\TableObject\Row;
use Earls\RhinoReportBundle\Module\Table\TableObject\Column;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\ActionInterface.
 */
interface ActionInterface
{
    public function setParameters(Column $column, array $rowData, Row $rowObject, array $arguments);

    public function setOptions($arguments);

    public function getOptions();

    public function setData();
}
