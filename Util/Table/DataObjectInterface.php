<?php

namespace Fuller\ReportBundle\Util\Table;

/**
 * Fuller\ReportBundle\Util\Table\DataObjectInterface
 */
interface DataObjectInterface
{

    public function __construct(array $data);

    public function getData();

    public function setData(array $data);

    public function getDatum($name);
}
