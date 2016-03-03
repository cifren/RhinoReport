<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnTblGroupDefinition
 * 
 * @ORM\Table(name="rhn_tbl_group_definition")
 * @ORM\Entity
 */
class RhnTblGroupDefinition extends baseDefinition
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
     * @var array $orderBy
     *
     * @ORM\Column(type="array")
     * 
     **/ 
    protected $orderBy;
    
     /**
     * @var string $groupBy
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     **/ 
    protected $groupBy;
    
     /**
     * @var array $rowSpan
     *
     * @ORM\Column(type="array")
     * 
     **/
    protected $rowSpan;
    
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
    
     /**
     * @var array $conditionalFormattings
     *
     * @ORM\Column(type="array")
     * 
     **/
    protected $conditionalFormattings;
    
    //********  Module Definition *********//
    /**
     * @var string $displayId
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $displayId;
    //********  END Module Definition *********//
    
    /**
     * @var RhnTblGroupDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblGroupDefinition", inversedBy="rhnTblGroupDefinitions")
     * @ORM\JoinColumn(name="rhn_tbl_group_definition_id", referencedColumnName="id")
     */
    protected $rhnTblGroupDefinition;
    
    /**
     * @var RhnTblMainDefinition
     *
     * @ORM\OneToOne(targetEntity="RhnTblMainDefinition", inversedBy="bodyDefinition")
     * @ORM\JoinColumn(name="rhn_tbl_main_definition_id", referencedColumnName="id")
     */
    protected $rhnTblMainDefinition;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblGroupDefinition", mappedBy="rhnTblGroupDefinition", cascade={"all"})
     */
    protected $rhnTblGroupDefinitions;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblRowDefinition", mappedBy="parent", cascade={"all"})
     */
    protected $rhnTblRowDefinitions;
    
    protected $initItem = false;
    
    public function __construct($displayId)
    {
        parent::__construct($displayId);
        $this->rhnTblGroupDefinitions = new ArrayCollection();
        $this->rhnTblRowDefinitions = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function addGroup($id)
    {
        $group = new RhnTblGroupDefinition($id);
        $group->setParent($this);
        $this->items[] = $group;
        $this->rhnTblGroupDefinitions[] = $group;

        return $group;
    }

    public function addRow(array $options)
    {
        $row = new RhnTblRowDefinition($options);
        $row->setParent($this);
        $this->items[] = $row;
        $this->rhnTblRowDefinitions[] = $row;

        return $row;
    }

    public function setParent($parent)
    {
        if ($parent instanceof RhnTblMainDefinition){
            $this->rhnTblMainDefinition = $parent;
        } elseif ($parent instanceof RhnTblGroupDefinition){
            $this->rhnTblGroupDefinition = $parent;
        }
        
        parent::setParent($parent);
    }

    public function getItems()
    {
        if(!$this->initItem){
            $this->items = array_merge($this->getRhnTblGroupDefinitions()->toArray(), $this->getRhnTblRowDefinitions()->toArray());
            $this->initItem = true;
        }
        return $this->items;
    }
    
    public function getRhnTblGroupDefinitions()
    {
       return $this->rhnTblGroupDefinitions;
    }
    
    public function getRhnTblRowDefinitions()
    {
        return $this->rhnTblRowDefinitions;
    }
}