<?php

namespace Earls\RhinoReportBundle\Tests\FunctionalTests\Tests\Database\Table\Definition;

use Doctrine\Common\Collections\Collection;
use Earls\RhinoReportBundle\Tests\FunctionalTests\Tests\Database\FixtureAwareTestCase;
use Earls\RhinoReportBundle\Report\Definition\ReportConfiguration;
use Earls\RhinoReportBundle\Tests\FunctionalTests\Tests\Database\Table\Definition\Fixture\ReportDefinitionFixture;

/**
 * Entity Tests.
 *
 * @group functional
 */
class EntityTest extends FixtureAwareTestCase
{
    public function setup()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $entityManager = $doctrine->getManager();

        $this->addFixture(new ReportDefinitionFixture());
        $this->executeFixtures();
    }

    public function testBuild()
    {
        $rptConfig = new ReportConfiguration();
        $rptConfig->setConfigReportDefinition($this->getReportDefinition());
        $rptConfig->setArrayData($this->getArrayDataClosure());

        $reportDefinition = $rptConfig->getConfigReportDefinition($this->getRequest(), array());
        $this->assertInstanceOf(
            'Earls\RhinoReportBundle\Report\Definition\ReportDefinition',
            $reportDefinition,
            'for reportDefinition'
        );

        $this->assertCount(1, $reportDefinition->getItems());

        $rptBuilder = $this->getContainer()->get('report.builder');
        $rptBuilder->setRequest($this->getRequest());
        $rptBuilder->setConfiguration($rptConfig);

        $rptBuilder->build();

        $reportObject = $rptBuilder->getReport();

        $this->assertInstanceOf('Earls\RhinoReportBundle\Report\ReportObject\Report', $reportObject);

        $this->assertCount(1, $reportObject->getItems());
    }

    public function testFilter()
    {
        $rptConfig = new ReportConfiguration();
        $rptConfig->setConfigReportDefinition($this->getReportDefinition());
        $rptConfig->setArrayData($this->getArrayDataClosure());

        $this->assertInstanceOf(Collection::class, $rptConfig->getFilter());

        $rptBuilder = $this->getContainer()->get('report.builder');
        $rptBuilder->setRequest($this->getRequest());
        $rptBuilder->setConfiguration($rptConfig);
        $rptBuilder->build();

        $reportObject = $rptBuilder->getReport();
        $this->assertInstanceOf('Earls\RhinoReportBundle\Report\ReportObject\Report', $reportObject);

        $reportFilter = $rptBuilder->getFilterForm();
        $this->assertInstanceOf('Symfony\Component\Form\Form', $reportFilter);

        $this->assertCount(1, $reportObject->getItems());
    }

    protected function getArrayDataClosure()
    {
        $closure = function ($data, $datafilter) {
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
        };

        return $closure;
    }

    protected function getReportDefinition()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $def = $em->getRepository('Earls\RhinoReportBundle\Entity\RhnReportDefinition')->findAll();

        $rptDef = array_shift((array_values($def)));

        return $rptDef;
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
