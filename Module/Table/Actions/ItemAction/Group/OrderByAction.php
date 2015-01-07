<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Group;

use Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Group\Action;
use Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper;
use \Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use \Earls\RhinoReportBundle\Module\Table\TableObject\Row;

/**
 * Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Group\OrderByAction
 *
 * reorder groups via rowUnique->column name, only for now
 */
class OrderByAction extends Action
{

    protected $retriever;
    protected $group;
    protected $path;
    protected $column;
    protected $options;

    public function __construct(TableRetrieverHelper $retriever)
    {
        $this->retriever = $retriever;
    }

    public function setGroup()
    {
        if (!$this->options['column']) {
            throw new \InvalidArgumentException('Argument \'column\' is missing in action \'' . $this->group->getDefinition()->getPath() . '\'');
        }

        if (!isset($this->options['order']) || !in_array($this->options['order'], array('desc', 'asc'))) {
            $this->options['order'] = 'asc';
        }

        $path = explode('.', $this->options['column']);

        $this->path = $path[0];
        $this->column = $path[1];

        if (!$this->column) {
            throw new \InvalidArgumentException('Argument \'column\' should look like this `\\table\\body\\item.column` in action \'' . $this->group->getDefinition()->getPath() . '\'');
        }

        $items = $this->group->getItems();
        $reserveItems = array();
        //take only item concerned by orderBy and reserve others item in other list
        foreach ($items as $key => $item) {
            if (!($item->getDefinition()->getPath() == $path[0])) {
                $reserveItems[$key] = $item;
                unset($items[$key]);
            } else {
                $reserveItems[$path[0]] = $item;
            }
        }

        //sort and conserve key
        uasort($items, array($this, 'cmp'));

        //insert $items in reserveItems
        $position = array_search($path[0], $reserveItems);
        array_splice($reserveItems, $position, 0, $items);

        $this->group->setItems($reserveItems);

        return null;
    }

    public function getOptions()
    {
        return array(
            'group' => null,
            'column' => null
        );
    }

    protected function cmp($a, $b)
    {
        if (!($a instanceof Group && $b instanceof Group))
            return 0;

        $aFilter = array_filter($a->getItems(), array($this, 'filterRowUnique'));
        $bFilter = array_filter($b->getItems(), array($this, 'filterRowUnique'));

        if (empty($aFilter)) {
            throw new \Exception('There is no row or no rowUnique in group `' . $a->getDefinition()->getPath() . '`');
        } elseif (empty($bFilter)) {
            throw new \Exception('There is no row or no rowUnique in group `' . $b->getDefinition()->getPath() . '`');
        }
        $aRow = array_shift($aFilter);
        $bRow = array_shift($bFilter);

        $aColumn = $aRow->getColumn($this->column);
        if ($aColumn == null) {
            throw new \Exception('Error in `OrderByAction` in group `' . $a->getDefinition()->getPath() . '`, column `'.$this->column.'` does not exist');
        }
        $aColumnData = (int) $aColumn->getData();

        $bColumn = $bRow->getColumn($this->column);
        if ($bColumn == null) {
            throw new \Exception('Error in `OrderByAction` in group `' . $b->getDefinition()->getPath() . '`, column `'.$this->column.'` does not exist');
        }
        $bColumnData = (int) $bColumn->getData();

        if ($aColumnData == $bColumnData) {
            return 0;
        }

        if ($this->options['order'] == 'asc') {
            return ( $aColumnData < $bColumnData ) ? -1 : 1;
        } else {
            return ( $aColumnData > $bColumnData ) ? -1 : 1;
        }
    }

    protected function filterRowUnique($var)
    {
        if ($var instanceof Row && $var->getDefinition()->getOption('unique')) {
            return true;
        }

        return false;
    }

}
