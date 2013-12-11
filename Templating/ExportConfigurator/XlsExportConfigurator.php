<?php

namespace Earls\RhinoReportBundle\Templating\ExportConfigurator;

use Earls\RhinoReportBundle\Definition\Table\TableDefinition;
use Earls\RhinoReportBundle\Templating\ExportConfigurator\ExportConfigurator;

/**
 * Earls\RhinoReportBundle\Templating\ExportConfigurator\XlsExportConfigurator
 */
class XlsExportConfigurator implements ExportConfigurator
{

    protected $styleTable = array();
    protected $pagebreak = false;
    protected $attr = array();
    protected $print = array();
    protected $column = array();
    protected $header = null;
    protected $rptInfo;

    public function setStyleTable(array $styleTable)
    {
        $this->styleTable = $styleTable;

        return $this;
    }

    public function getStyleTable()
    {
        return $this->styleTable;
    }

    public function setPagebreak($pagebreak)
    {
        $this->pagebreak = $pagebreak;

        return $this;
    }

    public function isPagebreak()
    {
        return $this->pagebreak;
    }

    public function setAttr(array $attr)
    {
        if ( isset($attr['class']) && !is_array($attr['class']) ) {
            throw new \Exception('Attribute `class´ for Excel Export `' . $this->getDefinition()->getPath() . '´ should be an array');
        }
        $this->attr = $attr;

        return $this;
    }

    public function getAttr()
    {
        return $this->attr;
    }

    public function setPrint(array $print)
    {
        $this->print = $print;

        return $this;
    }

    public function getPrint()
    {
        return $this->print;
    }

    public function setColumn(array $column)
    {
        $this->column = $column;

        return $this;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function setRptInfo(TableDefinition $rptInfo)
    {
        $this->rptInfo = $rptInfo;

        return $this;
    }

    public function getRptInfo()
    {
        return $this->rptInfo;
    }

}
