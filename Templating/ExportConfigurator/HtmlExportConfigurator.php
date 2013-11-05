<?php

namespace Fuller\ReportBundle\Templating\ExportConfigurator;

use Fuller\ReportBundle\Templating\ExportConfigurator\ExportConfigurator;

/**
 * Fuller\ReportBundle\Templating\ExportConfigurator\HtmlExportConfigurator
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
