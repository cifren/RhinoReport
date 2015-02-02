<?php

namespace Earls\RhinoReportBundle\Module\Table\Definition;

use Earls\RhinoReportBundle\Module\Table\Definition\AdvancedTableDefinitionBuilder;

/**
 * Earls\RhinoReportBundle\Module\Table\Definition\TableDefinitionBuilder
 */
class TableDefinitionBuilder extends AdvancedTableDefinitionBuilder
{

    public function group($id)
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function group()');
        }

        //only one group inside a group
        foreach ($this->getCurrentDefinition()->getItems() as $item) {
            if ($item instanceof GroupDefinition) {
                throwException(sprintf('The group "%s" can have only one group as child', $this->getCurrentDefinition()->getId()));
            }
        }

        return parent::group($id);
    }

    public function row()
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function row()');
        }

        //only one row inside a group
        foreach ($this->getCurrentDefinition()->getItems() as $item) {
            if ($item instanceof RowDefinition) {
                throwException(sprintf('The group "%s" can have only one row as child', $this->getCurrentDefinition()->getId()));
            }
        }

        return parent::row();
    }

    public function rowUnique()
    {
        if (!$this->getCurrentDefinition() instanceof GroupDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function row()');
        }

        //only one row inside a group
        foreach ($this->getCurrentDefinition()->getItems() as $item) {
            if ($item instanceof RowDefinition) {
                throw new \Exception(sprintf('The group "%s" can have only one row as child', $this->getCurrentDefinition()->getId()));
            }
        }

        return parent::rowUnique();
    }

    public function attr(array $attributes = array())
    {
        if (!$this->getCurrentDefinition() instanceof TableDefinition && !$this->getCurrentDefinition() instanceof HeadDefinition) {
            throw new \Exception('Expected argument of type "Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition" or "Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition", "' . get_class($this->getCurrentDefinition()) . '" given in function attr()');
        }

        return parent::attr($attributes);
    }

    public function buildDefinition()
    {
        $def = parent::buildDefinition();
        $def->setTemplate('DataTableTemplate');

        return $def;
    }

    public function build()
    {
        $this->defintionValidation();
        return $this;
    }

    protected function defintionValidation()
    {
        $this->groupValidation($this->getDefinition()->getBodyDefinition());
    }

    protected function groupValidation(GroupDefinition $group)
    {
        $groupCount = 0;
        $rowUniqueCount = 0;
        $rowCount = 0;

        foreach ($group->getItems() as $item) {
            if ($item instanceOf GroupDefinition) {
                $groupCount++;
                $this->groupValidation($item);
            } elseif ($item instanceof RowDefinition) {
                if ($item->getOption('unique')) {
                    $rowUniqueCount++;
                } else {
                    $rowCount++;
                }
            }
        }
        
        if($groupCount && $rowCount){
            throw new \Exception('A group can\'t contain a group item and a row item at the same time, you need to replace the row item by a rowUnique');
        }elseif($groupCount && !$rowUniqueCount && $group->getId() !== 'body'){
            throw new \Exception('A group always needs a rowUnique item when it contains a group item');
        }
    }

}
