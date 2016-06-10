<?php

namespace Earls\RhinoReportBundle\Tests\FunctionalTests\Tests\Database\Table\Definition\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Earls\RhinoReportBundle\Entity\RhnReportFilter;
use Earls\RhinoReportBundle\Entity\RhnReportDefinition;
use Earls\RhinoReportBundle\Entity\RhnTblMainDefinition;
use Earls\RhinoReportBundle\Entity\RhnTblColumnDefinition;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;

/**
 * Earls\RhinoReportBundle\Tests\Table\Definition\Fixture\ReportDefinitionFixture.
 */
class ReportDefinitionFixture implements FixtureInterface
{
    /**
     * We will create de equivalent of the php builder
     *  ->table('tableIng').
     **/
    public function load(ObjectManager $manager)
    {
        $filter1 = new RhnReportFilter();
        $filter1
            ->setName('catId')
            ->setType(ChoiceFilterType::class)
            ->setOptions(array(
                'choices' => array(
                    'Cat1' => 1,
                    'Cat2' => 2,
                    'Cat3' => 3,
                    ),
                ));
        $manager->persist($filter1);
        $filter2 = new RhnReportFilter();
        $filter2
            ->setName('sales')
            ->setType(NumberFilterType::class)
            ->setOptions(array());
        $manager->persist($filter2);

        $rptDef = new RhnReportDefinition();
        $rptDef
            ->addFilter($filter1)
            ->addFilter($filter2);
        $manager->persist($rptDef);

        $tableDef = new RhnTblMainDefinition();
        $tableDef //table def
            ->setDisplayId('tableIng')
            ->setPosition('position-2')
            ->setAttributes(array('class' => array('table-bordered', 'table-condensed')))
            ->getHeadDefinition() //head def
                ->setColumns(array(
                    'description' => 'Description',
                    'stock' => 'Stock',
                    'sales' => 'Sales',
                ))
                ->getParent()   //table def
            ->getBodyDefinition() //group def
                ->addGroup('category')
                ->setGroupBy('category')
                ->addRow(array('unique' => true))   //row def
                    ->createAndAddColumn('description', RhnTblColumnDefinition::TYPE_DISPLAY, 'category')   //column def
                    ->getParent()   //row def
                    ->setColSpan('description', 1)
                    ->createAndAddColumn('sales', RhnTblColumnDefinition::TYPE_DISPLAY) //column def
                        ->setGroupAction('sum', array('column' => '\tableIng\body\category\subcategory\items.sales'))
                        ->addAction('currency', array())
                    ->getParent()   //row def
                ->getParent()   //group def
                ->addGroup('subcategory')   //group def
                    ->setGroupby('subcategory')
                    ->addRow(array('unique' => true))   //row def
                        ->createAndAddColumn('description', RhnTblColumnDefinition::TYPE_DISPLAY, 'subcategory')    //column def
                        ->getParent()   //row def
                        ->setColSpan('description', 1)
                        ->createAndAddColumn('sales', RhnTblColumnDefinition::TYPE_DISPLAY) //column def
                            ->setGroupAction('sum', array('column' => '\tableIng\body\category\subcategory\items.sales'))
                            ->addAction('indent', array('space' => 2))
                        ->getParent()   //row def
                    ->getParent()   //group def
                    ->addGroup('items')   //group def
                        ->addRow(array())  //row def
                            ->createAndAddColumn('description', RhnTblColumnDefinition::TYPE_DISPLAY, 'item')->getParent() //row def
                            ->createAndAddColumn('stock', RhnTblColumnDefinition::TYPE_DISPLAY, 'stock')->getParent() //row def
                            ->createAndAddColumn('sales', RhnTblColumnDefinition::TYPE_DISPLAY, 'sales')->getParent() //row def
        ;

        $tableDef->setParent($rptDef);
        $manager->persist($tableDef);

        $manager->flush();
    }
}
