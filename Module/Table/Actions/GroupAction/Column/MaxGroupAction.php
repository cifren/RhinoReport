<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column;

use Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column\GroupAction;
use Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column\MaxGroupAction
 *
 */
class MaxGroupAction extends GroupAction
{

    protected $retriever;

    public function __construct(TableRetrieverHelper $retriever)
    {
        $this->retriever = $retriever;
    }

    public function setData()
    {
        if (!$this->options['column']) {
            throw new \InvalidArgumentException('Argument \'column\' is missing in group action \'' . $this->column->getDefinition()->getPath() . '\'');
        }
        $this->retriever->setTable($this->table);

        if ($this->options['fromGroup']) {
            $group = $this->retriever->getParentOrSubItemsFromGenericPath($this->options['fromGroup'], $this->column->getRow()->getGroup());
        } else {
            $group = $this->column->getRow()->getGroup();
        }
        $items = $this->retriever->getParentOrSubItemsFromGenericPath($this->options['column'], $group);
        $dataList = array();
        foreach ($items as $item) {
            $dataList[] = $item->getData();
        }
        
        return max($dataList);
    }

    public function getOptions()
    {
        return array(
            'column' => null,
            'fromGroup' => null
        );
    }

}
