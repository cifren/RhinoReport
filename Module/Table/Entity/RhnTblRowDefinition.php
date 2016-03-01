<?php

namespace Earls\RhinoReportBundle\Module\Table\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Entity\RhnTblRowDefinition
 * 
 * @ORM\Table(name="rhn_tbl_row_definition")
 * @ORM\Entity
 */
class RhnTblRowDefinition extends baseDefinition  implements ReportDefinitionInterface
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
     * @var array $options
     *
     * @ORM\Column(type="array")
     * 
     **/ 
    protected $options;
    
    
     /**
     * @var array $groupAction
     *
     * @ORM\Column(type="array")
     * 
     **/
    protected $groupAction;
    
     /**
     * @var array $extendingGroupAction
     *
     * @ORM\Column(type="array")
     * 
     **/
    protected $extendingGroupAction;
    
     /**
     * @var array $action
     *
     * @ORM\Column(type="array")
     * 
     **/
    protected $action;
    
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblColumnDefinition", inversedBy="rhnTblRowDefinition")
     */
    protected $columnDefinitions;

    public function addColumn($displayId, $type, array $exportConfigs, $dataId = null)
    {
        $column = new RhnTblColumnDefinition($displayId, $type, $exportConfigs, $dataId);
        $column->setParent($this);
        $this->columns[] = $column;

        return $this;
    }
}