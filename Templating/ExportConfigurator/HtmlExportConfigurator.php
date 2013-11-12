<?php

namespace Earls\RhinoReportBundle\Templating\ExportConfigurator;

use Earls\RhinoReportBundle\Templating\ExportConfigurator\ExportConfigurator;

/**
 * Earls\RhinoReportBundle\Templating\ExportConfigurator\HtmlExportConfigurator
 */
class HtmlExportConfigurator implements ExportConfigurator
{

    protected $attr = array();

    public function setAttr(array $attr)
    {
        $this->attr = $attr;

        return $this;
    }

    public function getAttr()
    {
        return $this->attr;
    }

}
