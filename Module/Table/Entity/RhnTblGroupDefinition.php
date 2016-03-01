<?php

namespace Earls\RhinoReportBundle\Module\Table\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Module\Table\Entity\RhnTblGroupDefinition
 * 
 * @ORM\Table(name="rhn_tbl_group_definition")
 * @ORM\Entity
 */
class RhnTblGroupDefinition extends baseDefinition  implements ReportDefinitionInterface
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
     * @ORM\Column(type="string", length=255)
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
    //********  END Module Definition *********//
    
    /**
     * @var RhnReportDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblGroupDefinition", inversedBy="rhnTblGroupDefinitions")
     * @ORM\JoinColumn(name="rhn_tbl_group_definition_id", referencedColumnName="id")
     */
    protected $rhnTblGroupDefinition;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblGroupDefinition", inversedBy="rhnTblGroupDefinition")
     */
    protected $rhnTblGroupDefinitions;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblRowDefinition", inversedBy="rhnTblGroupDefinition")
     */
    protected $rhnTblRowDefinitions;
    
    /**
     * @var RhnReportDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblMainDefinition", inversedBy="rhnTblGroupDefinitions")
     * @ORM\JoinColumn(name="rhn_tbl_main_definition_id", referencedColumnName="id")
     */
    protected $rhnTblMainDefinition;
    
    public function addGroup($id)
    {
        $group = new RhnTblGroupDefinition();
        $group->setDisplayId($id);
        $this->items[$id] = $group;

        return $group;
    }

    public function addRow(array $options)
    {
        $row = new RhnTblRowDefinition($options);
        $this->items[] = $row;

        return $row;
    }
}