<?php

namespace Earls\RhinoReportBundle\Factory;

use Earls\RhinoReportBundle\Model\Extension\ReportExtension;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Table;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Head;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Group;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Row;
use Earls\RhinoReportBundle\Model\Table\ReportObject\Column;
use Earls\RhinoReportBundle\Util\Table\DataManipulator;
use Earls\RhinoReportBundle\Util\Table\DataObject;
use Earls\RhinoReportBundle\Factory\Factory;
use Earls\RhinoReportBundle\Factory\TableRetriever;
use Earls\RhinoReportBundle\Definition\Table\GroupDefinition;
use Earls\RhinoReportBundle\Definition\Table\RowDefinition;
use Earls\RhinoReportBundle\Definition\Table\ColumnDefinition;
use Earls\RhinoReportBundle\Util\Table\DataObjectInterface;

/**
 *  Earls\RhinoReportBundle\Factory\TableFactory
 *
 */
class TableFactory extends Factory
{

    protected $data;
    protected $definition;
    protected $item;
    protected $dataGrouping;
    protected $reportTableExtension;
    protected $tableRetriever;
    protected $dataManipulator;
    protected $dataGroupActions = array();
    protected $groupActionAryExploded = array();

    public function __construct(ReportExtension $reportTableExtension, TableRetriever $tableRetriever, DataManipulator $dataManipulator)
    {
        $this->reportTableExtension = $reportTableExtension;
        $this->tableRetriever = $tableRetriever;
        $this->dataManipulator = $dataManipulator;
    }

    public function build()
    {
        $this->validationDefinition();

        $definitionData = $this->definition->getData();
        if (isset($definitionData)) {
            //select data directly from
            $data = $this->dataManipulator->selectData($definitionData, $this->definition->getId());
        } else {
            $data = $this->dataManipulator->selectData($this->data, $this->definition->getId());
        }
        $this->item = $this->createTable($this->definition, $data);
        $head = new Head('head', $this->definition->getHeadDefinition(), $this->item);

        //create object head
        $this->item->setHead($this->createHead($head));

        $body = new Group('body', $this->definition->getBodyDefinition()->getId(), $this->definition->getBodyDefinition(), $this->item, $this->item->getDataObject());
        $this->setListGroupActionOrExecuteActions($this->definition->getBodyDefinition(), $body);

        //create structure + data + simple action
        $body = $this->createGroupAndRow($body);

        //Almost Ready, missing GroupAction
        $this->item->setBody($body);

        //Fill up body with groupAction
        $body = $this->fillUpGroupAction($this->item);

        return $this;
    }

    protected function createTable($tableDefinition, $data)
    {
        $table = new Table($tableDefinition->getId(), $tableDefinition, $data);
        $table->setAttributes($tableDefinition->getAttributes());
        
        return $table;
    }

    protected function createHead(Head $head)
    {
        $headDefinition = $head->getDefinition();

        $head->setColumns($headDefinition->getColumns());

        $head->setAttributes($headDefinition->getAttributes());

        return $head;
    }

    protected function createGroupAndRow(Group $groupParent)
    {
        $groupDefinition = $groupParent->getDefinition();
        //get item rowDefinition or groupDefinition
        foreach ($groupDefinition->getItems() as $itemDefinition) {
            if ($itemDefinition instanceof GroupDefinition) {

                if ($itemDefinition->getGroupBy()) {
                    //Create sub-group attached
                    $groupsFromData = $this->createDataSubGroup($itemDefinition, $groupParent);

                    foreach ($groupsFromData as $group) {
                        $groupParent->addItem($group);
                    }
                } else {
                    $dataObj = $groupParent->getDataObject();
                    $this->reOrderData($groupDefinition, $dataObj);
                    $group = new Group($itemDefinition->getId(), $itemDefinition->getId(), $itemDefinition, $groupParent, $dataObj);
                    $this->reOrderData($itemDefinition, $group);

                    $group = $this->setListGroupActionOrExecuteActions($groupDefinition, $group);
                    $groupParent->addItem($group);
                }
            }

            if ($itemDefinition instanceof RowDefinition) {
                $rows = $this->createRows($itemDefinition, $groupParent);
                $groupParent->addItems($rows);
            }
        }

        return $groupParent;
    }

    protected function createDataSubGroup(GroupDefinition $groupDefinition, $groupParent)
    {
        $dataObj = clone $groupParent->getDataObject();
        $this->reOrderData($groupDefinition, $dataObj);

        $groups = array();
        $groupBy = $groupDefinition->getGroupBy();

        $groupByValue = $this->dataManipulator->getArrayUniqueValuesFromGroupBy($dataObj, $groupBy);

        foreach ($groupByValue as $subGroup) {

            //apply filter
            $groupData = $this->dataManipulator->getValueFilterByGroupBy($dataObj, $groupBy, $subGroup);

            //create new group with new data filtered
            $group = new Group($subGroup, $groupDefinition->getId(), $groupDefinition, $groupParent, $groupData);

            //reduce array for next group, remove useless data include in previous group
            $dataAry = array_diff_key($dataObj->getData(), $groupData->getData());
            $dataObj = $dataObj->setData($dataAry);

            $group = $this->createGroupAndRow($group);
            $group = $this->setListGroupActionOrExecuteActions($groupDefinition, $group);

            $groups[] = $group;
        }

        return $groups;
    }

    protected function reOrderData(GroupDefinition $groupDefinition, DataObjectInterface $objData)
    {
        $groupByAry = $groupDefinition->getOrderBy();
        if (empty($groupByAry))
            return;

        $data = $objData->getData();
        $objData->setData($this->reOrderDataRecursive($groupByAry, $data, $groupByAry[0]));
    }

    protected function reOrderDataRecursive($columns, $dataAry, $currentColumn)
    {
        //get order asc or desc for column, default asc
        $columnOrder = explode(' ', $currentColumn);
        if (isset($columnOrder[1]) && ($columnOrder[1] == 'asc' || $columnOrder[1] == 'desc')) {
            $order = $columnOrder[1];
        } else {
            $order = 'asc';
        }

        //sort and conserve key
        usort($dataAry, function($a, $b) use ($currentColumn, $order) {
                    if ($order == 'asc') {
                        return ( $a[$currentColumn] < $b[$currentColumn] ) ? -1 : 1;
                    } else {
                        return ( $a[$currentColumn] > $b[$currentColumn] ) ? -1 : 1;
                    };
                });

        array_shift($columns);
        if (empty($columns)) {
            return $dataAry;
        }
        $filteredAry = $this->dataManipulator->getArrayGroupBy(new DataObject($dataAry), $currentColumn);

        $dataAry = array();
        foreach ($filteredAry as $subGroup) {
            $subGroup = $this->reOrderDataRecursive($columns, $subGroup->getData(), $columns[0]);
            $dataAry = array_merge($dataAry, $subGroup);
        }

        return $dataAry;
    }

    //Apply action on group or set list group actions
    protected function setListGroupActionOrExecuteActions($definition, $item)
    {
        //if GroupAction on groupObject dont exectute others Actions
        if ($definition->hasGroupAction() || $definition->hasExtendingGroupAction()) {
            $this->dataGroupActions[$definition->getPath()][] = $item->getFullPath(); //column path
        } elseif ($definition->hasActions()) {
            $objectNameExploded = explode('\\', get_class($item));
            //get last one
            end($objectNameExploded);
            $objectName = $objectNameExploded[key($objectNameExploded)];

            $executeActionsOn = 'executeActionsOn' . $objectName;
            $this->$executeActionsOn($definition, $item);
        }

        return $item;
    }

    protected function executeActionsOnColumn(ColumnDefinition $definition, Column $item)
    {
        $actionColumnDefinitions = $definition->getActions();

        foreach ($actionColumnDefinitions as $actionColumnDefinition) {
            $action = $this->reportTableExtension->getTableActionOnColumn($actionColumnDefinition['name']);
            $action->setParameters($item, $item->getParent()->getDataObject()->getData(), $item->getParent(), $actionColumnDefinition['arg']);
            $item->setData($action->setData());
        }
    }

    protected function executeActionsOnRow(RowDefinition $definition, Row $item)
    {
        $actionRowDefinitions = $definition->getActions();

        foreach ($actionRowDefinitions as $actionRowDefinition) {
            $rowAction = $this->reportTableExtension->getTableActionOnRow($actionRowDefinition['name']);
            $rowAction->setParameters($item, $actionRowDefinition['arg']);
            $rowAction->setRow();
        }
    }

    protected function executeActionsOnGroup(GroupDefinition $definition, Group $item)
    {
        $actionGroupDefinitions = $definition->getActions();

        foreach ($actionGroupDefinitions as $actionGroupDefinition) {
            $action = $this->reportTableExtension->getTableActionOnGroup($actionGroupDefinition['name']);
            $action->setParameters($item, $actionGroupDefinition['arg']);
            $action->setGroup();
        }
    }

    protected function createRows(RowDefinition $rowDefinition, Group $groupParent)
    {
        $data = $groupParent->getDataObject()->getData();

        //take the first value
        if ($data && $rowDefinition->getOption('unique')) {
            $data = array(array_shift($data));
        }

        $rows = array();

        //No Data
        if (!$data || empty($data)) {
            $def = $rowDefinition->getColumns();
            $columnDefinition = array_shift($def);
            $dataObject = new DataObject(array());
            $row = new row(0, $rowDefinition, $groupParent, $dataObject);
            $rows[] = $this->createRowNoData($row, $columnDefinition);
        }

        $i = 0;
        //create a row for each datum in array data
        foreach ($data as $datum) {
            $dataObject = new DataObject($datum);
            $row = new row($i, $rowDefinition, $groupParent, $dataObject);
            $row->setAttributes($rowDefinition->getAttributes());

            $colSpanCount = 0;
            //all columns with dataId
            foreach ($rowDefinition->getColumns() as $columnDefinition) {
                $displayId = $columnDefinition->getDisplayId();
                $column = new Column($displayId, $columnDefinition, $row);
                $column->setAttributes($columnDefinition->getAttributes());
                $column->setFormatExcel($columnDefinition->getFormatExcel());

                //dont add column if colspan active
                if ($colSpanCount) {
                    $colSpanCount--;
                    continue;
                }

                //add attribute colspan
                $colSpan = $columnDefinition->getColSpan();
                if ($colSpan) {
                    $colSpanCount = $colSpan - 1;
                    $column->setAttribute('colspan', $colSpan);
                }

                //if sprintf / concat / calculation etc...
                $baseData = $columnDefinition->getBaseData();
                if (!empty($baseData)) {
                    if ($baseData['type'] == 'displayId') {
                        if ($row->getColumn($baseData['column'])) {
                            $column->setData($row->getColumn($baseData['column'])->getData());
                        }
                    } elseif ($baseData['type'] == 'dataId') {
                        $column->setData($datum[$baseData['column']]);
                    }
                }

                $row->addColumn($column->getId(), $column);
                //if GroupAction do nothing and dont exectute others Actions
                if ($columnDefinition->hasGroupAction() || $columnDefinition->hasExtendingGroupAction()) {
                    $this->dataGroupActions[$columnDefinition->getPath()][] = $column->getFullPath(); //column path
                    continue;
                }

                //apply actions like href, sprintf etc...
                if ($columnDefinition->hasActions()) {
                    $this->executeActionsOnColumn($columnDefinition, $column);
                }
            }
            //if GroupAction dont exectute others Actions
            if ($rowDefinition->hasGroupAction() || $rowDefinition->hasExtendingGroupAction()) {
                $this->dataGroupActions[$rowDefinition->getPath()][] = $row->getFullPath(); //row path
            } else {
                //apply actions on row like highlight etc...
                if ($rowDefinition->hasActions()) {
                    $this->executeActionsOnRow($rowDefinition, $row);
                }
            }

            $rows[] = $row;
            $i++;
        }

        return $rows;
    }

    protected function createRowNoData(Row $row, ColumnDefinition $columnDefinition, $display = 'No Data')
    {
        $row->setAttributes($row->getDefinition()->getAttributes());

        $colspanNoData = count($this->definition->getHeadDefinition());

        $column = new Column('nodata', $columnDefinition, $row, $display);
        $column->setData($display);
        $column->setAttributes($columnDefinition->getAttributes());
        $column->setColSpan($colspanNoData);

        $row->addColumn($column->getId(), $column);

        return $row;
    }

    protected function fillUpGroupAction(Table $table)
    {
        $this->tableRetriever->setTable($table);
        $sortedGroupAction = $this->sortByDependence($this->groupActionAryExploded);

        $this->executeGroupActions($sortedGroupAction);

        return $table;
    }

    //topological_sort = http://en.wikipedia.org/wiki/Topological_sorting
    //Code from https://www.calcatraz.com/blog/php-topological-sort-function-384
    private function sortByDependence(array $groupActionsExploded)
    {
        //$nodeids, $edges
        $nodeids = array_keys($groupActionsExploded);

        $edges = array();
        foreach ($groupActionsExploded as $action => $dependences) {
            foreach ($dependences as $dependence) {
                $edges[] = array($dependence, $action);
            }
        }

        $L = $S = $nodes = array();
        foreach ($nodeids as $id) {
            $nodes[$id] = array('in' => array(), 'out' => array());
            foreach ($edges as $e) {
                if ($id == $e[0]) {
                    $nodes[$id]['out'][] = $e[1];
                }
                if ($id == $e[1]) {
                    $nodes[$id]['in'][] = $e[0];
                }
            }
        }
        foreach ($nodes as $id => $n) {
            if (empty($n['in']))
                $S[] = $id;
        }
        while (!empty($S)) {
            $L[] = $id = array_shift($S);
            foreach ($nodes[$id]['out'] as $m) {
                $nodes[$m]['in'] = array_diff($nodes[$m]['in'], array($id));
                if (empty($nodes[$m]['in'])) {
                    $S[] = $m;
                }
            }
            $nodes[$id]['out'] = array();
        }
        foreach ($nodes as $n) {
            if (!empty($n['in']) or !empty($n['out'])) {
                return null; // not sortable as graph is cyclic
            }
        }

        return $L;
    }

    private function executeGroupActions(array $sortedGroupActionDefinition)
    {
        foreach ($sortedGroupActionDefinition as $genericGroupAction) {
            if (!isset($this->dataGroupActions[$genericGroupAction])) {
                continue;
            }

            foreach ($this->dataGroupActions[$genericGroupAction] as $dataGroupAction) {
                //retrieve column from column->path ex : \table:%:table\group:%:body:@:body\group:%:category:@:FOOD\row:%:0\column:%:ec
                $item = $this->tableRetriever->getItemFromDataPath($dataGroupAction);

                if ($item instanceof Column) {
                    $this->executeGroupActionOnColumn($item);
                } elseif ($item instanceof Row) {
                    $this->executeGroupActionOnRow($item);
                } elseif ($item instanceof Group) {
                    $this->executeGroupActionOnGroup($item);
                }
            }
        }
    }

    protected function executeGroupActionOnColumn(Column $item)
    {
        $baseData = $item->getDefinition()->getBaseData();
        if (!empty($baseData)) {
            if ($baseData['type'] == 'displayId') {
                $item->setData($item->getParent()->getColumn($baseData['column'])->getData());
            }
        }

        if ($item->getDefinition()->hasGroupAction()) {
            $actionGroupDefinition = $item->getDefinition()->getGroupAction();

            //execute group action
            $groupAction = $this->reportTableExtension->getTableGroupActionOnColumn($actionGroupDefinition['name']);
            $groupAction->setParameters($item, $this->item, $actionGroupDefinition['arg']);
            $item->setData($groupAction->setData());
        }


        //execute action
        if ($item->getDefinition()->hasActions()) {
            $this->executeActionsOnColumn($item->getDefinition(), $item);
        }
    }

    protected function executeGroupActionOnRow(Row $row)
    {
        if ($row->getDefinition()->hasGroupAction()) {
            $actionGroupDefinition = $row->getDefinition()->getGroupAction();

            //execute group action
            $groupAction = $this->reportTableExtension->getTableGroupActionOnRow($actionGroupDefinition['name']);
            $groupAction->setParameters($row, $this->item, $actionGroupDefinition['arg']);
            $groupAction->setRow();
        }

        //execute action
        if ($row->getDefinition()->hasActions()) {
            $this->executeActionsOnRow($row->getDefinition(), $row);
        }
    }

    protected function executeGroupActionOnGroup(Group $group)
    {
        if ($group->getDefinition()->hasGroupAction()) {
            $actionGroupDefinition = $group->getDefinition()->getGroupAction();

            //execute group action
            $groupAction = $this->reportTableExtension->getTableGroupActionOnGroup($actionGroupDefinition['name']);
            $groupAction->setParameters($group, $this->item, $actionGroupDefinition['arg']);
            $groupAction->setGroup();
        }

        //execute action
        if ($group->getDefinition()->hasActions()) {
            $this->executeActionsOnGroup($group->getDefinition(), $group);
        }
    }

    protected function validationDefinition()
    {
        $this->validationGroupAction();
    }

    //check if there is no infinite loop in dependences
    //make a list of generic name group actions
    protected function validationGroupAction()
    {
        //Get list group actions generic with direct dependence ex: array(\tableGflByReason\body\item.totCost' => array(0 => string '\tableGflByReason\body\item.totCostData'))
        //get for body
        $groupActionAry = $this->getGenericGroupAction(array(), $this->definition->getBodyDefinition());
        //get for children of body
        $groupActionAry = $this->getGroupActionDependence($this->definition->getBodyDefinition(), 0, $groupActionAry);

        //Get list exploded
        $this->groupActionAryExploded = $this->getGroupActionDependenceExploded($groupActionAry);

        $conflict = array();
        //check if action is a part of dependence, if yes that means there is a loop
        foreach ($this->groupActionAryExploded as $actionName => $dependences) {
            if (in_array($actionName, $dependences)) {
                $conflict[] = $actionName;
            }
        }

        //send an error for all loop value
        if (count($conflict) > 0) {
            throw new \InvalidArgumentException('Group action named \'' . implode('\', \'', $conflict) . '\' seems to create a loop in group action dependence');
        }
    }

    protected function getGroupActionDependence($itemDefinition, $lvl, $groupActionAry = array()) //$genericPath = '\\body', $groupActionAry = array())
    {
        $i = 0;
        foreach ($itemDefinition->getItems() as $item) {
            $i++;
            if ($item instanceof GroupDefinition) {
                $groupActionAry = $this->getGenericGroupAction($groupActionAry, $item);
                $groupActionAry = $this->getGroupActionDependence($item, $lvl + 1, $groupActionAry);
            }

            if ($item instanceof RowDefinition) {
                foreach ($item->getColumns() as $columnDefinition) {

                    $groupActionAry = $this->getGenericGroupAction($groupActionAry, $columnDefinition);
                }
                $groupActionAry = $this->getGenericGroupAction($groupActionAry, $item);
            }
        }

        return $groupActionAry;
    }

    public function getGenericGroupAction($groupActionAry, $item)
    {
        if ($item->hasGroupAction() || $item->hasExtendingGroupAction()) {
            //if action or if extending action
            if ($item->hasGroupAction()) {
                $actionDefinition = $item->getGroupAction();
            } elseif ($item->hasExtendingGroupAction()) {
                $actionDefinition = $item->getExtendingGroupAction();
            }

            $dependences = $actionDefinition['dependences'];
            $actionPath = $item->getPath();

            //add dependences to action index
            $groupActionAry[$actionPath] = $dependences;
        }

        return $groupActionAry;
    }

    protected function getGroupActionDependenceExploded($groupActionAry)
    {
        $dependenceAry = array();
        foreach ($groupActionAry as $key => $actionGroup) {
            //add action to parent list
            $parentActionAry = array($key);

            //get all dependences for this action
            $dependenceAry[$key] = $this->getRecursiveDependency($groupActionAry, $key, $actionGroup, $parentActionAry);
        }

        return $dependenceAry;
    }

    protected function getRecursiveDependency($groupActionAry, $actionName, $dependences, $parentActionAry)
    {
        //if no dependence
        if (empty($dependences)) {
            return array();
        }

        $dependencesAry = array();

        foreach ($dependences as $dependenceAction) {

            //check if already exist in array, if yes continue next dependence
            if (in_array($dependenceAction, $dependencesAry)) {
                continue;
            }

            $dependencesAry[$actionName][] = $dependenceAction;
            //check if dependence is a parent, if yes stop function, that means there is a loop for this action
            if (in_array($dependenceAction, $parentActionAry)) {
                return $dependencesAry[$actionName];
            }

            //add to parent list
            $parentActionAry[] = $dependenceAction;

            //check if dependence is really a group action
            if (!isset($groupActionAry[$dependenceAction])) {
                throw new \InvalidArgumentException("Group action named '$dependenceAction' doesn't exist");
            }

            //get all dependences
            $dependencesAry[$actionName] = array_merge($dependencesAry[$actionName], $this->getRecursiveDependency($groupActionAry, $dependenceAction, $groupActionAry[$dependenceAction], $parentActionAry));
        }

        return $dependencesAry[$actionName];
    }

}
