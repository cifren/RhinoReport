<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Module\Table\Definition\ColumnDefinition as baseDefinition;

/**
 * Earls\RhinoReportBundle\Entity\RhnTblColumnDefinition.
 *
 * @ORM\Table(name="rhn_tbl_column_definition")
 * @ORM\Entity
 */
class RhnTblColumnDefinition extends baseDefinition
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     **/
    protected $dataId;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $baseData;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $formulaExcel;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned": true}, nullable=true)
     **/
    protected $colSpan;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     **/
    protected $type;

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
    protected $actions;

    //********  Module Definition *********//
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     **/
    protected $displayId;
    //********  END Module Definition *********//

    /**
     * @var RhnTblRowDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnTblRowDefinition", inversedBy="columnDefinitions")
     * @ORM\JoinColumn(name="rhn_tbl_row_definition_id", referencedColumnName="id")
     */
    protected $parent;

    public function getId()
    {
        return $this->id;
    }

    public function setParent($parent)
    {
        if (!$parent) {
            $this->parent = null;

            return $this;
        }

        return parent::setParent($parent);
    }
}
