<?php

namespace Earls\RhinoReportBundle\Tests\Entity;

use Earls\RhinoReportBundle\Entity\RhnReportDefinition;

class RhnReportDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEntity()
    {
        $entity = new RhnReportDefinition();
        $entity->setTemplate('lol');
        $this->assertEquals('lol', $entity->getTemplate());
    }
}
