<?php

namespace Earls\RhinoReportBundle\Tests\Table\Definition;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Entity Tests
 */
class EntityTest extends KernelTestCase
{
    
    public function testBuild()
    {
        
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