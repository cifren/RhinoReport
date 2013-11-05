<?php

namespace Fuller\ReportBundle\Definition;

/*
 *  Fuller\ReportBundle\Definition\ReportDefinitionInterface
 *
 */

interface ReportDefinitionInterface
{
    public function setFactoryService($serviceName);

    public function getFactoryService();

}
