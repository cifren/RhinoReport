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