<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Row;

use Earls\RhinoReportBundle\Model\Table\ReportObject\Row;

/**
 *  Earls\RhinoReportBundle\Factory\Table\Action\Row\ActionInterface
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
