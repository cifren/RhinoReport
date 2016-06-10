<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Earls\RhinoReportBundle\Module\Table\Definition\GroupDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnTblGroupDefinition.
 *
 * @ORM\Table(name="rhn_tbl_group_definition")
 * @ORM\Entity
 */
class RhnTblGroupDefinition extends baseDefinition
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     **/
    protected $id;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $orderBy;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     **/
    protected $groupBy;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $rowSpan;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $groupAction;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $extendingGroupAction;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $action;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $conditionalFormattings;

    //********  Module Definition *********//
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
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
     * @ORM\OneToMany(targetEntity="RhnTblGroupDefinition", mappedBy="rhnTblGroupDefinition", cascade={"remove", "persist"}, orphanRemoval=true)
     */
    protected $rhnTblGroupDefinitions;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RhnTblRowDefinition", mappedBy="parent", cascade={"remove", "persist"}, orphanRemoval=true)
     */
    protected $rhnTblRowDefinitions;

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
        $group = new self($id);
        $group->setParent($this);
        $this->addItem($group);
        $this->rhnTblGroupDefinitions[] = $group;

        return $group;
    }

    public function addRow(array $options)
    {
        $row = new RhnTblRowDefinition($options);
        $row->setParent($this);
        $this->addItem($row);
        $this->rhnTblRowDefinitions[] = $row;

        return $row;
    }

    public function setParent($parent)
    {
        if (!$parent) {
            $this->rhnTblGroupDefinition = null;
            $this->rhnTblMainDefinition = null;

            return $this;
        }
        if ($parent instanceof RhnTblMainDefinition) {
            $this->rhnTblMainDefinition = $parent;
        } elseif ($parent instanceof self) {
            $this->rhnTblGroupDefinition = $parent;
        }

        return parent::setParent($parent);
    }

    public function getParent()
    {
        return $this->getRhnTblMainDefinition() ? $this->getRhnTblMainDefinition() : $this->getRhnTblGroupDefinition();
    }

    public function removeNotFromList(ArrayCollection $items)
    {
        $this->removeGroupNotFromList($items);
        $this->removeRowNotFromList($items);

        return $this;
    }

    public function removeGroupNotFromList(ArrayCollection $items)
    {
        foreach ($this->getRhnTblGroupDefinitions() as $group) {
            if (!$items->contains($group)) {
                $this->getRhnTblGroupDefinitions()->removeElement($group);
                $this->getItems()->removeElement($group);
                $group->setParent(null);
            }
        }

        return $this;
    }

    public function removeRowNotFromList(ArrayCollection $items)
    {
        foreach ($this->getRhnTblRowDefinitions() as $row) {
            if (!$items->contains($row)) {
                $this->getRhnTblRowDefinitions()->removeElement($row);
                $this->getItems()->removeElement($row);
                $row->setParent(null);
            }
        }

        return $this;
    }

    public function getItems()
    {
        $this->items = array_merge($this->getRhnTblGroupDefinitions()->toArray(), $this->getRhnTblRowDefinitions()->toArray());
        uasort($this->items, function ($a, $b) {
            $av = $a->getItemOrder();
            $bv = $b->getItemOrder();
            if ($av == $bv) {
                return 0;
            }

            return ($av < $bv) ? -1 : 1;
        });
        $this->items = new ArrayCollection($this->items);

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

    public function getRhnTblGroupDefinition()
    {
        return $this->rhnTblGroupDefinition;
    }

    public function getRhnTblMainDefinition()
    {
        return $this->rhnTblMainDefinition;
    }
}
