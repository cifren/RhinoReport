<?php 

namespace Earls\RhinoReportBundle\Tests\FunctionalTests\Tests\Database\Table\Definition\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Earls\RhinoReportBundle\Entity\RhnReportDefinition;
use Earls\RhinoReportBundle\Entity\RhnTblMainDefinition;
use Earls\RhinoReportBundle\Entity\RhnTblColumnDefinition;

/**
 * Earls\RhinoReportBundle\Tests\Table\Definition\Fixture\ReportDefinitionFixture
 */
class ReportDefinitionFixture implements FixtureInterface
{
    /**
     * We will create de equivalent of the php builder
     *  ->table('tableIng')
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
     **/ 
    public function load(ObjectManager $manager)
    {
        $rptDef = new RhnReportDefinition();
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
                    'sales' => 'Sales'
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