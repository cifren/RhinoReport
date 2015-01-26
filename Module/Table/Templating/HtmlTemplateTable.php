<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating;

use Earls\RhinoReportBundle\Report\Templating\Model\ModuleTemplate;

/**
 * Description of HtmlTemplateTable
 *
 * @author cifren
 */
class HtmlTemplateTable extends ModuleTemplate
{

    protected $simpleTable;

    public function getSimpleTable()
    {
        return $this->simpleTable;
    }

    public function setSimpleTable($simpleTable)
    {
        $this->simpleTable = $simpleTable;
        return $this;
    }

}
