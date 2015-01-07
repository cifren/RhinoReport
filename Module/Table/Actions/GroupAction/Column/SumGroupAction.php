<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column;

use Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column\GroupAction;
use Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\GroupAction\Column\SumGroupAction
 *
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
            throw new \InvalidArgumentException('Argument \'column\' is missing in group action \'' . $this->column->getDefinition()->getPath() . '\'');
        }
        $this->retriever->setTable($this->table);

        $items = $this->retriever->getParentOrSubItemsFromGenericPath($this->options['column'], $this->column->getRow()->getGroup());
        $sum = 0;
        foreach ($items as $item) {
            $sum += $item->getData();
        }

        return $sum;
    }

    public function getOptions()
    {
        return array(
            'group' => null,
            'column' => null
        );
    }

}
