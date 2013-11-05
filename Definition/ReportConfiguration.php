<?php

namespace Fuller\ReportBundle\Definition;

use Symfony\Component\HttpFoundation\Request;
use Fuller\ReportBundle\Model\Report;

/*
 * Fuller\ReportBundle\Configuration\ReportConfiguration
 */

abstract class ReportConfiguration implements ReportConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getFilter()
    {
        return null;
    }

    public function hasFilter()
    {
        if ($this->getFilter()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getFilterModel()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigReportDefinition(Request $request, $dataFilter)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getArrayData(array $data, $dataFilter)
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getReportObject(Report $object, $dataFilter)
    {
        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryBuilder()
    {
        return null;
    }

    public function hasQueryBuilder()
    {
        if ($this->getQueryBuilder()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAvailableExport()
    {
        return array('html' => 'Display onscreen', 'xls' => 'Excel');
    }

}
