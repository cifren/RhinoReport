<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Column;

use Fuller\ReportBundle\Model\Table\ReportObject\Row;
use Fuller\ReportBundle\Model\Table\ReportObject\Column;

/**
 *  Fuller\ReportBundle\Factory\Table\Action\Column\ActionInterface
 *
 */
interface ActionInterface
{

    public function setParameters(Column $column, array $rowData, Row $rowObject, array $arguments);

    public function setOptions($arguments);

    public function getOptions();

    public function setData();
}
