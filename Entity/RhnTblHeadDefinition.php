<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnTblHeadDefinition
 * 
 * @ORM\Table(name="rhn_tbl_head_definition")
 * @ORM\Entity
 */
class RhnTblHeadDefinition extends baseDefinition
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
     * @var array $columns
     *
     * @ORM\Column(type="array")
     * 
     **/
    protected $columns;
    
    //********  Module Defintion *********//
    
    /**
     * @var string $displayId
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $displayId = 'head_def';
    //********  END Module Defintion *********//
    
    /**
     * @var RhnTblMainDefinition
     *
     * @ORM\OneToOne(targetEntity="RhnTblMainDefinition", inversedBy="headDefinition")
     */
    protected $parent;
    
    public function getId()
    {
        return $this->id;
    }
}