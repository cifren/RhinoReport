<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnTblColumnDefinition
 * 
 * @ORM\Table(name="rhn_tbl_column_definition")
 * @ORM\Entity
 */
class RhnTblColumnDefinition extends baseDefinition
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
     * @var string $dataId
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     **/ 
    protected $dataId;
    
    /**
     * @var array $baseData
     *
     * @ORM\Column(type="array")
     * 
     **/ 
    protected $baseData;
    
    /**
     * @var array $formulaExcel
     *
     * @ORM\Column(type="array")
     * 
     **/ 
    protected $formulaExcel;
    
    /**
     * @var integer $colSpan
     *
     * @ORM\Column(type="integer", options={"unsigned": true}, nullable=true)
     * 
     **/ 
    protected $colSpan;
    
    /**
     * @var string $type
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $type;
    
    //********  Module Defintion *********//
    /**
     * @var string $displayId
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $displayId;
    //********  END Module Defintion *********//
    
    /**
     * @var RhnTblRowDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblRowDefinition", inversedBy="columnsDefinitions")
     * @ORM\JoinColumn(name="rhn_tbl_row_definition_id", referencedColumnName="id")
     */
    protected $parent;
    
    public function getId()
    {
        return $this->id;
    }
    
}