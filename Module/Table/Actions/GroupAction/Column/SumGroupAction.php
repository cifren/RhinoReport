<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column;

use Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column\SumGroupAction.
 */
class SumGroupAction extends GroupAction
{
    protected $retriever;

    public function __construct(TableRetrieverHelper $retriever)
    {
        $this->retriever = $retriever;
    }

    public function setData()
    {
        if (!$this->options['column']) {
            throw new \InvalidArgumentException('Argument \'column\' is missing in group action SUM on \''.$this->column->getDefinition()->getPath().'\'');
        }
        $this->retriever->setTable($this->table);

        if ($this->options['fromGroup']) {
            $group = $this->retriever->getParentOrSubItemsFromGenericPath($this->options['fromGroup'], $this->column->getRow()->getGroup());
        } else {
            $group = $this->column->getRow()->getGroup();
        }
        $items = $this->retriever->getParentOrSubItemsFromGenericPath($this->options['column'], $group);

        $sum = 0;
        foreach ($items as $item) {
            $sum += $item->getData();
        }

        return $sum;
    }

    public function getOptions()
    {
        return array(
            'fromGroup' => null,
            'column' => null,
        );
    }
}
