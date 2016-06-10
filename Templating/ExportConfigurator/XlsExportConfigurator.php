<?php

namespace Earls\RhinoReportBundle\Templating\ExportConfigurator;

use Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition;

/**
 * Earls\RhinoReportBundle\Templating\ExportConfigurator\XlsExportConfigurator.
 */
class XlsExportConfigurator implements ExportConfigurator
{
    protected $styleTable = array();
    protected $tableOddEven = array(
        'column' => array('active' => true, 'classes' => array('even' => 'evenColumnClass', 'odd' => 'oddColumnClass')),
        'row' => array('active' => true, 'classes' => array('even' => 'evenRowClass', 'odd' => 'oddRowClass')),
    );
    protected $pagebreak = false;
    protected $attr = array();
    protected $print = array();
    protected $protection = array(
        'activated' => false,
        'options' => array(
            //permissions
            'protectAllCells' => true, //will set the protect on all cells
            'selectLockedCells' => true, //if selectLockedCells is true, selectUnlockedCells will be true be default
            'selectUnlockedCells' => true,
            'formatCells' => false,
        ),
    );
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

    public function setTableOddEven(array $tableOddEven)
    {
        $this->tableOddEven = array_merge($this->tableOddEven, $tableOddEven);

        return $this;
    }

    public function getTableOddEven()
    {
        return $this->tableOddEven;
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
        if (isset($attr['class']) && !is_array($attr['class'])) {
            throw new \Exception('Attribute `class´ for Excel Export `'.$this->getDefinition()->getPath().'´ should be an array');
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

    public function getProtection()
    {
        return $this->protection;
    }

    public function setProtection(array $protection)
    {
        $this->protection = array_replace_recursive($this->protection, $protection);

        return $this;
    }
}
