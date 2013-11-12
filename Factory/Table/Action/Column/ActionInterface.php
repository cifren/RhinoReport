<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Model\Table\ReportObject\Row;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Column;

/**
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\ActionInterface
 *
 */
interface ActionInterface
{

    public function setParameters(Column $column, array $rowData, Row $rowObject, array $arguments);

    public function setOptions($arguments);

    public function getOptions();

    public function setData();
}
