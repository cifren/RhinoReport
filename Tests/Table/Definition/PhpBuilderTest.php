<?php

namespace Earls\RhinoReportBundle\Tests\Table\Definition;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Earls\RhinoReportBundle\Report\Definition\ReportDefinitionBuilder;

/**
 * PhpBuilder Tests
 */
class PhpBuilderTest extends KernelTestCase
{
    private $container;
    
    public function testReportBuilder()
    {
        $defBuilder = $this->getContainer()->get('report.definition.builder');
        $defBuilder
                ->bar('vf')
                    ->position('position-1')
                    ->labels('category')
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
                    ->labels('category')
                    ->dataset('stock', 'Item in stock', array())
                ->end()
                ->table('tableIng')
                    ->position('position-2')
                    ->attr(array('class' => array('table-bordered', 'table-condensed')))                 
                    ->head()
                        ->headColumns(array(
                            'description' => 'Description',
                            'stock' => 'Stock',
                            'sales' => 'Sales'
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
                            'sales' => 'Sales'
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
        ;
        $definition = $defBuilder->build()->getDefinition();
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Report\Definition\ReportDefinition',
            $definition
        );
        
    }

    public function testTableDefintion()
    {
        $defBuilder = $this->getContainer()->get('report.definition.builder');
        
        $defBuilder
            ->table('first')
                ->head()
                    ->headColumns(array('store_name', 'blank', 'type', 'blank2', 'timeLaps'))
                ->end()
            ->end()
        ;
        $definition = $defBuilder->build()->getDefinition();
        $columns = $definition->getItem('first')->getHeadDefinition()->getColumns();
        foreach($columns as $col){
            $simpleColmunAry[] = $col['label'];
        }
        
        $this->assertEquals(
            array('store_name', 'blank', 'type', 'blank2', 'timeLaps'), 
            $simpleColmunAry
        );
    }
    
    protected function getContainer()
    {
        self::bootKernel();
        return self::$kernel->getContainer();
    }
}