<?php

namespace Earls\RhinoReportBundle\Tests\FunctionalTests\Tests\Database\Table\Definition\Stub;

use Symfony\Component\HttpFoundation\Request;
use Earls\RhinoReportBundle\Report\Definition\ReportConfiguration;

class ReportConfigurationStub extends ReportConfiguration
{
    public function getFilter()
    {
        return new FilterType();
    }

    public function getConfigReportDefinition(Request $request, $dataFilter)
    {
        $definitionBuilder = $this->getReportDefinitionBuilder();

        $definitionBuilder
                ->bar('vf')
                    ->position('position-1')
                    ->label('category')
                    ->dataset('sales', 'Item Sold', array(
                        'fillColor' => '#f09777',
                        'strokeColor' => '#EFB9A5',
                        'highlightFill' => '#EF602C',
                        'highlightStroke' => '#f09777',
                            ))
                    ->dataset('stock', 'Item in stock', array())
                ->end()
                ->bar('er')
                    ->position('position-3')
                    ->label('category')
                    ->dataset('stock', 'Item in stock', array())
                ->end()
                ->table('tableIng')
                    ->position('position-2')
                    ->attr(array('class' => array('table-bordered', 'table-condensed')))
                    ->head()
                        ->headColumns(array(
                            'description' => 'Description',
                            'stock' => 'Stock',
                            'sales' => 'Sales',
                        ))
                    ->end()
                    ->body()
                        ->group('category')
                            ->groupBy('category')
                            ->rowUnique()
                                ->column('description', 'category')
                                ->columnSpan('description', 1)
                                ->column('sales')
                                    ->groupAction('sum', array('column' => '\tableIng\body\category\subcategory\items.sales'))
                                ->end()
                            ->end()
                            ->group('subcategory')
                                ->groupBy('subcategory')
                                ->rowUnique()
                                    ->column('description', 'subcategory')
                                    ->columnSpan('description', 1)
                                    ->column('sales')
                                        ->groupAction('sum', array('column' => '\tableIng\body\category\subcategory\items.sales'))
                                    ->end()
                                ->end()

                                ->group('items')
                                    ->row()
                                        ->column('description', 'item')
                                        ->column('stock', 'stock')
                                        ->column('sales', 'sales')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->advancedtable('tableRecipe')
                    ->position('position-2')
                    ->attr(array('class' => array('table-bordered', 'table-condensed')))
                    ->head()
                        ->headColumns(array(
                            'description' => 'Description',
                            'stock' => 'Stock',
                            'sales' => 'Sales',
                        ))
                    ->end()
                    ->body()
                        ->group('category')
                            ->groupBy('category')
                            ->rowUnique()
                                ->column('description', 'category')
                                ->columnSpan('description', 1)
                                ->column('sales')
                                    ->groupAction('sum', array('column' => '\tableRecipe\body\category\subcategory\items.sales'))
                                ->end()
                            ->end()
                            ->group('subcategory')
                                ->groupBy('subcategory')
                                ->rowUnique()
                                    ->column('description', 'subcategory')
                                    ->columnSpan('description', 1)
                                    ->column('sales')
                                        ->groupAction('sum', array('column' => '\tableRecipe\body\category\subcategory\items.sales'))
                                    ->end()
                                ->end()

                                ->group('items')
                                    ->row()
                                        ->column('description', 'item')
                                        ->column('stock', 'stock')
                                        ->column('sales', 'sales')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ;

        return $definitionBuilder->getBuildItem();
    }

    public function getArrayData(array $data, $dataFilter)
    {
        $array = array();
        $array[] = array(
            'category' => 'food',
            'subcategory' => 'app',
            'item' => 'fish',
            'stock' => '2',
            'sales' => '4',
        );
        $array[] = array(
            'category' => 'food',
            'subcategory' => 'app',
            'item' => 'fish2',
            'stock' => '3',
            'sales' => '5',
        );
        $array[] = array(
            'category' => 'food',
            'subcategory' => 'entree',
            'item' => 'meat1',
            'stock' => '11',
            'sales' => '6',
        );
        $array[] = array(
            'category' => 'food',
            'subcategory' => 'entree',
            'item' => 'meat2',
            'stock' => '11',
            'sales' => '6',
        );
        $array[] = array(
            'category' => 'liquor',
            'subcategory' => 'beer',
            'item' => 'vodka',
            'stock' => '3',
            'sales' => '4',
        );
        $array[] = array(
            'category' => 'liquor',
            'subcategory' => 'beer',
            'item' => 'wiskey',
            'stock' => '9',
            'sales' => '2',
        );
        $array[] = array(
            'category' => 'liquor',
            'subcategory' => 'wine',
            'item' => 'vodka',
            'stock' => '3',
            'sales' => '4',
        );
        $array[] = array(
            'category' => 'liquor',
            'subcategory' => 'wine',
            'item' => 'wiskey',
            'stock' => '9',
            'sales' => '2',
        );

        if ($dataFilter['cat'] === 1) {
            $array = array_filter($array, function ($var) {
                return $var['category'] == 'food';
            });
        } elseif ($dataFilter['cat'] === 2) {
            $array = array_filter($array, function ($var) {
                return $var['category'] == 'liquor';
            });
        }

        return $array;
    }
}
