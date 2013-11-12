<?php

namespace Earls\RhinoReportBundle\Factory;

use Earls\RhinoReportBundle\Util\Table\DataObjectInterface;

/*
 *  Earls\RhinoReportBundle\Factory\ReportFactoryInterface
 *
 */

interface ReportFactoryInterface
{

    public function setData(DataObjectInterface $data);

    public function build();

    public function getItem();
}
