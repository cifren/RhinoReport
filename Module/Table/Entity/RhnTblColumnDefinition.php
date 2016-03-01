<?php

namespace Earls\RhinoReportBundle\Module\Table\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Entity\RhnTblColumnDefinition
 * 
 * @ORM\Table(name="rhn_tbl_column_definition")
 * @ORM\Entity
 */
class RhnTblColumnDefinition extends baseDefinition  implements ReportDefinitionInterface
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
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="integer", options={"unsigned": true})
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
     * @var RhnTblRowDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblRowDefinition", inversedBy="columnsDefinitions")
     * @ORM\JoinColumn(name="rhn_tbl_row_definition_id", referencedColumnName="id")
     */
    protected $rhnTblRowDefinition;
    
}