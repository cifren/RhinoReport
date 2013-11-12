<?php

namespace Earls\RhinoReportBundle\Definition\Table;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Earls\RhinoReportBundle\Definition\Table\TableDefinition;

/*
 *  Earls\RhinoReportBundle\Definition\Table\HeadDefinition
 *
 */

class HeadDefinition extends Definition
{

    protected $parent;
    protected $columns;

    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumn($displayId, $displayName, $attr = array())
    {
        $this->columns[$displayId] = array('label' => $displayName, 'attr' => $attr);

        return $this;
    }

    public function setColumns(array $columnNames)
    {
        foreach ($columnNames as $id => $column) {
            if (is_array($column)) {
                if(isset($column['attr'])){
                    if(!is_array($column['attr'])){
                        throw new Exception('Attr in headcolumn should be a array');
                    }
                    if(isset($column['attr']['class']) && !is_array($column['attr']['class'])){
                        $column['attr']['class'] = array($column['attr']['class']);
                    }
                }
                $this->setColumn($id, $column['label'], $column['attr'] ? $column['attr'] : null);
            } else {
                $this->setColumn($id, $column);
            }
        }

        return $this;
    }

    public function setParent($parent)
    {
        if (!$parent instanceof TableDefinition) {
            throw new UnexpectedTypeException($parent, 'Earls\RhinoReportBundle\Definition\Table\TableDefinition');
        }
        parent::setParent($parent);
    }

}
