<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Factory\Table\Action\Column\Action;

/*
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\EntityFieldAction
 *
 */

class EntityFieldAction extends Action
{

    protected $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setData()
    {
        $em = $this->doctrine->getEntityManager($this->options['em']);
        $value = (int) $this->rowData[$this->options['dataId']];
        
        //check for an ID
        if (!is_int($value) || 0 == $value)
            throw new \Symfony\Component\Config\Definition\Exception\InvalidDefinitionException('Only accepts integers and not 0. Input was: ' . $value . '. For the data `' . $this->options['dataId'] . '` and class `' . $this->options['class'] . '` and field `' . $this->options['property'] . '`');

        $object = $em->getRepository($this->options['class'])->find($this->rowData[$this->options['dataId']]);
        $getter = 'get' . ucfirst($this->options['property']);
        $objectData = $object->$getter();

        return $objectData;
    }

}
