<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Bar\Definition\DatasetDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnBarDatasetDefinition
 * 
 * @ORM\Table(name="rhn_bar_dataset_definition")
 * @ORM\Entity
 */
class RhnBarDatasetDefinition extends baseDefinition
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
    
    /**
     * @var string $labelColumn
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $labelColumn;
    
    /**
     * @var string $dataColumn
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $dataColumn;
    
    /**
     * @var array $options
     *
     * @ORM\Column(type="array")
     * 
     **/ 
    protected $options;
    
    /**
     * @var RhnBarDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnBarDefinition", inversedBy="datasets")
     * @ORM\JoinColumn(name="rhn_bar_definition_id", referencedColumnName="id")
     */
    protected $parent;
    
    public function __construct()
    {
        // nothing
    }
    
    public function getId(){
        return $this->id;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
}