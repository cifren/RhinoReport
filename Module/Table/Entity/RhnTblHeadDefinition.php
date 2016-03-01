<?php

namespace Earls\RhinoReportBundle\Module\Table\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\HeadDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Entity\RhnTblHeadDefinition
 * 
 * @ORM\Table(name="rhn_tbl_head_definition")
 * @ORM\Entity
 */
class RhnTblHeadDefinition extends baseDefinition  implements ReportDefinitionInterface
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
     * @var RhnReportDefinition
     *
     * @ORM\OneToOne(targetEntity="RhnTblMainDefinition", inversedBy="rhnTblHeadDefinition")
     */
    protected $rhnReportDefinition;
}