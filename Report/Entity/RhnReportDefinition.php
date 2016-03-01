<?php

namespace Earls\RhinoReportBundle\Report\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Earls\RhinoReportBundle\Report\Entity\RhnReportDefinition
 * 
 * @ORM\Table(name="rhn_report_definition")
 * @ORM\Entity
 */
class RhnReportDefinition extends baseReportDefinition  implements ReportDefinitionInterface
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
     * @var string $template
     *
     * @ORM\Column(type="string", length=255)
     * 
     **/ 
    protected $template;
    
}