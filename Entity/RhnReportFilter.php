<?php

namespace Earls\RhinoReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Earls\RhinoReportBundle\Report\Definition\ReportFilter as baseReportFilter;

/**
 * Earls\RhinoReportBundle\Entity\RhnReportFilter.
 *
 * @ORM\Table(name="rhn_report_filter")
 * @ORM\Entity
 */
class RhnReportFilter extends baseReportFilter
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
     * @ORM\Column(type="string", length=255)
     **/
    protected $name;

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
    protected $options;

    /**
     * @var RhnReportDefinition
     *
     * @ORM\ManyToOne(targetEntity="RhnReportDefinition", inversedBy="filters")
     * @ORM\JoinColumn(name="rhn_report_definition_id", referencedColumnName="id")
     */
    protected $parent;

    public function __construct()
    {
        //nothing
    }

    public function getId()
    {
        return $this->id;
    }
}
