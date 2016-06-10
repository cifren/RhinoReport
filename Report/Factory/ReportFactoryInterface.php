<?php

namespace Earls\RhinoReportBundle\Report\Factory;

use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;

/*
 *  Earls\RhinoReportBundle\Report\Factory\ReportFactoryInterface
 *
 */

interface ReportFactoryInterface
{
    public function setData(DataObjectInterface $data);

    public function build();

    public function getItem();
}
