<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Row;

use Earls\RhinoReportBundle\Module\Table\TableObject\Row;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Row\ActionInterface
 *
 */
interface ActionInterface
{

    public function setParameters(Row $row, array $arguments);

    public function setOptions(array $arguments);

    public function getOptions();

    public function setRow();

    public function getDependences();
}
