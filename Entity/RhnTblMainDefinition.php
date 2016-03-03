<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition as baseTableDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnTblMainDefinition
 * 
 * @ORM\Table(name="rhn_tbl_main_definition")
 * @ORM\Entity
 */
class RhnTblMainDefinition extends baseTableDefinition
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
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $position;
    //********  END Module Defintion *********//
    
    /**
     * @var RhnTblHeadDefinition
     *
     * @ORM\OneToOne(targetEntity="RhnTblHeadDefinition", inversedBy="parent", cascade={"all"})
     * @ORM\JoinColumn(name="rhn_tbl_head_definition_id", referencedColumnName="id")
     */
    protected $headDefinition;
    
    /**
     * @var RhnTblGroupDefinition
     *
     * @ORM\OneToOne(targetEntity="RhnTblGroupDefinition", mappedBy="rhnTblMainDefinition", cascade={"all"})
     */
    protected $bodyDefinition;
    
    /**
     * @var RhnReportDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnReportDefinition", inversedBy="rhnTblMainDefinitions")
     * @ORM\JoinColumn(name="rhn_report_definition_id", referencedColumnName="id")
     */
    protected $parent;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setHeadDefinition($headDefinition)
    {
        $this->headDefinition = $headDefinition;
        return $this;
    }

    public function getHeadDefinition()
    {
        if(!$this->headDefinition){
            $this->setHeadDefinition(new RhnTblHeadDefinition());
            $this->headDefinition->setParent($this);
        }
        return $this->headDefinition;
    }
    
    public function setBodyDefinition($bodyDefinition)
    {
        $this->bodyDefinition = $bodyDefinition;
        return $this;
    }

    public function getBodyDefinition()
    {
        if(!$this->bodyDefinition){
            $this->setBodyDefinition(new RhnTblGroupDefinition('body'));
            $this->bodyDefinition->setParent($this);
        }
        return $this->bodyDefinition;
    }
}