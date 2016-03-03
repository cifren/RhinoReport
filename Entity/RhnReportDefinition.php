<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Earls\RhinoReportBundle\Report\Definition\ReportDefinition as baseReportDefinition;
use Earls\RhinoReportBundle\Report\Definition\ModuleDefinitionInterface;

/**
 * Earls\RhinoReportBundle\Entity\RhnReportDefinition
 * 
 * @ORM\Table(name="rhn_report_definition")
 * @ORM\Entity
 */
class RhnReportDefinition extends baseReportDefinition
{
    protected $itemMatch = array('table' => 'tblMainDefinitions');
    
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     **/ 
    protected $id;
    
    /**
     * @var string $template
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/
    protected $template = 'DefaultTemplate';
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblMainDefinition", mappedBy="parent", cascade={"all"})
     */
    protected $rhnTblMainDefinitions;
    
    protected $initItem = false;

    public function __construct()
    {
        $this->rhnTblMainDefinitions = new ArrayCollection();
        
        parent::__construct();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function addItem(ModuleDefinitionInterface $item)
    {
        parent::addItem($item);
        
        if(!array_key_exists($item->getModuleType(), $this->itemMatch)){
            throw new \UnexpectedValueException("{$item->getModuleType()} is not recognized module type");
        }
        $this->{$this->itemMatch[$item->getModuleType()]}[] = $item;
        
        return $this;
    }

    public function getItems()
    {
        if(!$this->initItem){
            $this->items = $this->rhnTblMainDefinitions;
            $this->initItem = true;
        }
        return $this->items;
    }
}