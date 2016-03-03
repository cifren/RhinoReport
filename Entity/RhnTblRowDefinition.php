<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnTblRowDefinition
 * 
 * @ORM\Table(name="rhn_tbl_row_definition")
 * @ORM\Entity
 */
class RhnTblRowDefinition extends baseDefinition
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
     * @var string $displayId
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $displayId = 'row';
    //********  END Module Defintion *********//
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblColumnDefinition", mappedBy="parent", cascade={"all"})
     */
    protected $columnDefinitions;
    
    /**
     * @var RhnTblGroupDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblGroupDefinition", inversedBy="rhnTblGroupDefinitions")
     * @ORM\JoinColumn(name="rhn_tbl_group_definition_id", referencedColumnName="id")
     */
    protected $parent;
    
    public function getId()
    {
        return $this->id;
    }

    public function createAndAddColumn($displayId, $type, $dataId = null)
    {
        $column = new RhnTblColumnDefinition($displayId, $type, $dataId);
        $column->setParent($this);
        $this->columnDefinitions[] = $column;

        return $column;
    }
}