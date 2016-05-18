<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Earls\RhinoReportBundle\Module\Table\Definition\RowDefinition as baseDefinition;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition;

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
     * @ORM\OneToMany(targetEntity="RhnTblColumnDefinition", mappedBy="parent", cascade={"remove", "persist"})
     */
    protected $columnDefinitions;
    
    /**
     * @var RhnTblGroupDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblGroupDefinition", inversedBy="rhnTblRowDefinitions")
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
    
    public function addColumn(ColumnDefinition $columnDefinition)
    {
        parent::addColumn($columnDefinition);
        $columnDefinition->setParent($this);
        
        return $columnDefinition;
    }
    
    public function setColumns($columns = array())
    {
        $columnCollection = new ArrayCollection($columns);
        
        $this->removeColumnsNotFromList($columnCollection);
        
        parent::setColumns($columns);
        
        return $this;
    }
    
    public function removeColumnsNotFromList(ArrayCollection $items)
    {
        // remove parent from previous columns
        foreach($this->getColumns() as $col){
            if(!$items->contains($col)){
                $this->getColumns()->removeElement($col);
                $col->setParent(null);
            }
        }
        return $this;
    }

    public function setParent($parent)
    {
        if(!$parent){
            $this->parent = null;
            return $this;
        }
        
        return parent::setParent($parent);
    }
}