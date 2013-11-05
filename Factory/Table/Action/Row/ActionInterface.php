<?php

namespace Fuller\ReportBundle\Factory\Table\Action\Row;

use Fuller\ReportBundle\Model\Table\ReportObject\Row;

/**
 *  Fuller\ReportBundle\Factory\Table\Action\Row\ActionInterface
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
