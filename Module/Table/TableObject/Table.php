<?php

namespace Earls\RhinoReportBundle\Module\Table\TableObject;

use Earls\RhinoReportBundle\Module\Table\TableObject\Head;
use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\TableObject;
use Earls\RhinoReportBundle\Module\Table\Util\DataObjectInterface;

/*
 * Earls\RhinoReportBundle\Module\Table\TableObject\Table
 */

class Table extends TableObject
{

    protected $head;
    protected $type;

    public function __construct($id, $definition, DataObjectInterface $data)
    {
        $this->type = 'table';
        $this->setDataObject($data);

        parent::__construct($id, $definition, null);
    }

    public function setHead(Head $head)
    {
        $this->head = $head;

        return $this;
    }

    public function getHead()
    {
        return $this->head;
    }

    public function setBody(Group $body)
    {
        $this->body = $body;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setFooter(Group $footer)
    {
        $this->footer = $footer;

        return $this;
    }

    public function getFooter()
    {
        return $this->footer;
    }

    public function getParentPath()
    {
        return '';
    }

}
