<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Bar\Definition\DatasetDefinition;
use Earls\RhinoReportBundle\Module\Bar\Definition\BarDefinition as baseBarDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnBarDefinition
 * 
 * @ORM\Table(name="rhn_bar_definition")
 * @ORM\Entity
 */
class RhnBarDefinition extends baseBarDefinition
{
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     **/ 
    protected $id;
    
    //********  Module Defintion *********//
    /**
     * @var string $template
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $template = 'DefaultTemplate';
    
    /**
     * @var string $displayId
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $displayId;
    
    /**
     * @var string $position
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     **/ 
    protected $position;
    //********  END Module Defintion *********//
    
    /**
     * @var string $labelColumn
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     **/ 
    protected $labelColumn;
    
    /**
     * @var array $options
     *
     * @ORM\Column(type="array")
     * 
     **/ 
    protected $options;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnBarDatasetDefinition", mappedBy="parent", cascade={"remove", "persist"}, orphanRemoval=true)
     */
    protected $datasets;
    
    /**
     * @var RhnReportDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnReportDefinition", inversedBy="bars")
     * @ORM\JoinColumn(name="rhn_report_definition_id", referencedColumnName="id")
     */
    protected $parent;
    
    public function __construct()
    {
        // nothing
    }
    
    public function getId(){
        return $this->id;
    }

    public function addDataset(DatasetDefinition $dataset)
    {
        $dataset->setParent($this);
        
        return parent::addDataset($dataset);
    }

    public function setParent($parent)
    {
        if(!$parent){
            $this->parent = null;
            return $this;
        }
        
        return parent::setParent($parent);
    }
}