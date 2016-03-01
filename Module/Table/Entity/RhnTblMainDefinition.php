<?php

namespace Earls\RhinoReportBundle\Module\Table\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\TableDefinition as baseTableDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Entity\RhnTblMainDefinition
 * 
 * @ORM\Table(name="rhn_tbl_main_definition")
 * @ORM\Entity
 */
class RhnTblMainDefinition extends baseTableDefinition  implements ReportDefinitionInterface
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
    protected $template;
    
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
     * @ORM\OneToOne(targetEntity="RhnTblHeadDefinition", inversedBy="rhnTblMainDefinition")
     * @ORM\JoinColumn(name="rhn_tbl_head_definition_id", referencedColumnName="id")
     */
    protected $rhnTblHeadDefinition;
    
    /**
     * @var RhnTblGroupDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblGroupDefinition", inversedBy="rhnTblMainDefinition")
     */
    protected $rhnTblGroupDefinitions;
    
    /**
     * @var RhnReportDefinition
     *
     * @ORM\OneToOne(targetEntity="RhnReportDefinition")
     * @ORM\JoinColumn(name="rhn_report_definition_id", referencedColumnName="id")
     */
    protected $rhnReportDefinition;
    
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
        $this->rhnTblGroupDefinition = $bodyDefinition;
        return $this;
    }

    public function getBodyDefinition()
    {
        if(!$this->rhnTblGroupDefinition){
            $this->setBodyfinition(new RhnTblGroupDefinition('body'));
            $this->rhnTblGroupDefinition->setParent($this);
        }
        return $this->rhnTblGroupDefinition;
    }
}