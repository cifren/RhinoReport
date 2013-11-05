<?php

namespace Fuller\ReportBundle\Factory;

use Fuller\ReportBundle\Util\Table\DataObjectInterface;

/*
 *  Fuller\ReportBundle\Factory\ReportFactoryInterface
 *
 */

interface ReportFactoryInterface
{

    public function setData(DataObjectInterface $data);

    public function build();

    public function getItem();
}
