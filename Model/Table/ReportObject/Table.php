<?php

namespace Fuller\ReportBundle\Model\Table\ReportObject;

use Fuller\ReportBundle\Model\Table\ReportObject\Head;
use Fuller\ReportBundle\Model\Table\ReportObject\Group;
use Fuller\ReportBundle\Model\Table\ReportObject\TableObject;
use Fuller\ReportBundle\Util\Table\DataObjectInterface;

/*
 * Fuller\ReportBundle\Model\Table\ReportObject\Table
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

    public function getParentPath()
    {
        return '';
    }

}
