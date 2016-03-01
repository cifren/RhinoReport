<?php

namespace Earls\RhinoReportBundle\Tests\Table\Definition;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Earls\RhinoReportBundle\Report\Definition\ReportDefinitionBuilder;
use Earls\RhinoReportBundle\Tests\Table\Definition\Stub\ReportConfigurationStub;
use Earls\RhinoReportBundle\Report\Definition\ReportBuilder;

/**
 * PhpBuilder Tests
 */
class PhpBuilderTest extends KernelTestCase
{
    private $container;
    
    //simple configuration
    public function testReportDefinitionBuilder()
    {
        $defBuilder = $this->getContainer()->get('report.definition.builder');
        $defBuilder
                //add bar graph
                ->bar('vf')
                    //defined the position on the template
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
        $reportDefinition = $defBuilder->getBuildItem();
        
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Report\Definition\ReportDefinition',
            $reportDefinition,
            'for reportDefinition'
        );
        
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Module\Bar\Definition\BarDefinition',
            $reportDefinition->getItem('vf'),
            'for barDefinition id "vf"'
        );
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Module\Bar\Definition\BarDefinition',
            $reportDefinition->getItem('er'),
            'for barDefinition id "er"'
        );
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition',
            $reportDefinition->getItem('tableIng'),
            'for barDefinition id "tableIng"'
        );
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition',
            $reportDefinition->getItem('tableRecipe'),
            'for barDefinition id "tableRecipe"'
        );
    }

    public function testTableDefinitionBuilder()
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
    
    public function testReportBuilder()
    {
        $rptConfig = new ReportConfigurationStub(
            $this->getContainer()->get('report.definition.builder')
        );
        $reportDefinition = $rptConfig->getConfigReportDefinition($this->getRequest(), array());
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Report\Definition\ReportDefinition',
            $reportDefinition,
            'for reportDefinition'
        );
        $this->assertCount(4, $reportDefinition->getItems());
        
        $rptBuilder = $this->getContainer()->get('report.builder');
        $rptBuilder->setRequest($this->getRequest());
        $rptBuilder->setConfiguration($rptConfig);
        
        $rptBuilder->build();

        $reportObject = $rptBuilder->getReport();
        
        $this->assertInstanceOf('Earls\RhinoReportBundle\Report\ReportObject\Report', $reportObject);
        
        $this->assertCount(4, $reportObject->getItems());
    }
    
    protected function getContainer()
    {
        self::bootKernel();
        return self::$kernel->getContainer();
    }
    
    protected function getRequest()
    {
        $stub = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                     ->getMock();
        
        // Configure the stub
        $stub
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap(array('stub_filter' => array('cat' => 1))));  
             
         return $stub;
    }
}