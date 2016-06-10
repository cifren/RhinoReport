<?php

namespace Earls\RhinoReportBundle\Module\Table\Util;

/**
 * Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface.
 */
interface DataObjectInterface
{
    public function __construct(array $data);

    public function getData();

    public function setData(array $data);

    public function getDatum($name);
}
