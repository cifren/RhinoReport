<?php

namespace Fuller\ReportBundle\Factory\Table\GroupAction\Column;

use Fuller\ReportBundle\Model\Table\ReportObject\Table;
use Fuller\ReportBundle\Model\Table\ReportObject\Column;

/**
 *  Fuller\ReportBundle\Factory\Table\GroupAction\Column\GroupActionInterface
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
