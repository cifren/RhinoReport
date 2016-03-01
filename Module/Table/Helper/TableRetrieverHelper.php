<?php

namespace Earls\RhinoReportBundle\Module\Table\Helper;

use Earls\RhinoReportBundle\Module\Table\TableObject\Table;
use Earls\RhinoReportBundle\Module\Table\TableObject\Group;
use Earls\RhinoReportBundle\Module\Table\TableObject\Row;

/*
 * Earls\RhinoReportBundle\Module\Table\Helper\TableRetrieverHelper
 */

class TableRetrieverHelper
{

    protected $table;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function getItemFromDataPath($dataPath)
    {
        $parsedPath = $this->parsePath($dataPath);

        //unset path '\' and 'table' and 'body' => default base
        unset($parsedPath[0], $parsedPath[1]);

        $item = $this->table->getBody();

        foreach ($parsedPath as $dataPath) {
            switch ($dataPath['type']) {
                case 'row';
                case 'group':
                    $item = $item->getItem($dataPath['partialPath']);
                    break;
                case 'column':
                    $item = $item->getColumn($dataPath['genericId']);
                    break;
            }
        }

        return $item;
    }

    public function parsePath($path)
    {
        $itemPathes = explode('\\', $path);

        //remove '/' at the begining
        if (strpos($path, '\\') === 0) {
            unset($itemPathes[0]);
        }

        $parsedPath = array();
        foreach ($itemPathes as $itemPath) {

            $details = explode(':%:', $itemPath);
            $type = $details[0];

            $details = explode(':@:', $details[1]);

            if (count($details) > 1) {
                $genericId = $details[0];
                $dataId = $details[1]; //only for group with group by
            } else {
                $genericId = $details[0];
                $dataId = null;
            }

            $parsedPath[] = array(
                'type' => $type,
                'genericId' => $genericId,
                'dataId' => $dataId,
                'fullPath' => $path,
                'partialPath' => $itemPath
            );
        }

        return $parsedPath;
    }

    public function getParentOrSubItemsFromGenericPath($fullGenericPath, Group $item)
    {
        //determine if fullGenericPath is a sub item or it is parent
        $genericPath = str_replace($item->getDefinition()->getPath(), '', $fullGenericPath);

        $parseGenericPath = explode('\\', $genericPath);

        //get subItems, if it s still parsable and $item->getDefinition()->getPath() is a part of $fullGenericPath
        if (count($parseGenericPath) >= 2 && $genericPath != $fullGenericPath) {
            return $this->getSubItemsFromGenericPath($fullGenericPath, $item);
        } elseif ($genericPath == '') { //get item

            return array($item);
        } else {//get parentItems

            return $this->getParentItemsFromGenericPath($fullGenericPath, $item);
        }
    }

    //item must be a group
    //only search for sub-items
    //$fullGenericPath = '\body\category\subCat\stockbook.ec';
    //$item  => example path '\body\category';
    //function recursive
    protected function getSubItemsFromGenericPath($fullGenericPath, Group $item)
    {
        if ($item->getDefinition()->getPath() == $fullGenericPath) {
            return array($item);
        }

        //take only sub-items path, for example result will be 'subCat\stockbook.ec'
        $genericPath = str_replace($item->getDefinition()->getPath(), '', $fullGenericPath);

        //$item->getDefinition()->getPath() is not a part of $fullGenericPath, $fullGenericPath is not sub item of $item
        if ($genericPath == $fullGenericPath) {
            throw new \InvalidArgumentException('Issue on argument \''.$fullGenericPath.'\'');
        }

        //parse path
        $parseGenericPath = explode('\\', $genericPath);

        //current group to call, can be a group with a column example \stockbook.ec, $parseGenericPath[0] is always empty because path begin by '\'
        $currentGroup = $parseGenericPath[1];

        //$genericPath is empty or worst !!
        if (count($parseGenericPath) < 2) {
            throw new \InvalidArgumentException('Issue on argument \''.$fullGenericPath.'\'');
        }

        //check if column asked
        $column = explode('.', $currentGroup);

        //unexpected error, that means there is more than one '.'
        if (count($column) > 2) {
            throw new \InvalidArgumentException('Issue on argument \'' . $fullGenericPath . '\'');
        }
        $items = array();

        //can be a group or a column
        if (count($column) > 1) {
            $currentGroup = $column[0];
            foreach ($this->getGroups($currentGroup, $item) as $group) {
                $items = array_merge($items, $this->getColumns($column[1], $group));
            }
        } else { //that means asking for groups
            foreach ($this->getGroups($currentGroup, $item) as $group) {
                $items = array_merge($items, $this->getSubItemsFromGenericPath($fullGenericPath, $group));
            }
        }

        return $items;
    }

    ////item must be a group
    //only search for parent-items
    //$fullGenericPath = '\body\category\iSoldTot.ec';
    //$item  => example path '\body\category\subCat\catObj';
    protected function getParentItemsFromGenericPath($fullGenericPath, Group $item)
    {
        //check if column asked
        $column = explode('.', $fullGenericPath);

        //unexpected error, that means there is more than one '.'
        if (count($column) > 2) {
            throw new \InvalidArgumentException('Issue on argument \'' . $fullGenericPath . '\'');
        }
        //only group path
        $groupPath = $column[0];

        //select only difference between 2 path
        $differencePath = str_replace($groupPath, '', $item->getDefinition()->getPath());
var_dump($fullGenericPath);
var_dump($item->getDefinition()->getPath());
var_dump($differencePath);
        //means $groupPath is not a parent of itemPath
        if ($differencePath == $item->getDefinition()->getPath()) {
            throw new \InvalidArgumentException('Issue on argument \'' . $fullGenericPath . '\'');
        }

        $parseDifferencePath = explode('\\', $differencePath);

        foreach ($parseDifferencePath as $groupRelativePath) {
            if ($item->getParent() == null) {
                throw new \InvalidArgumentException('Issue on argument \'' . $fullGenericPath . '\'');
            }
            if ($groupRelativePath != '') {
                 $item = $item->getParent();
            }
        }
        //is column
        if (count($column) > 1) {
            return $this->getColumns($column[1], $item);
        } else {//is a group

            return $item;
        }
    }

    public function getGroups($genericId, Group $item)
    {
        $groups = array();
        foreach ($item->getItems() as $subItem) {
            if ($subItem instanceOf Group) {
                if ($subItem->getGenericId() == $genericId) {
                    $groups[] = $subItem;
                }
            }
        }

        return $groups;
    }

    public function getColumns($displayId, Group $item)
    {
        $columns = array();
        foreach ($item->getItems() as $subItem) {
            if ($subItem instanceOf Row) {
                $columns[] = $subItem->getColumn($displayId);
            }
        }

        return $columns;
    }

}
