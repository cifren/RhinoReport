<?php

namespace Earls\RhinoReportBundle\Report\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Earls\RhinoReportBundle\Report\Entity\ReportDefinition
 * 
 * @ORM\Table(name="lnb_data_report")
 * @ORM\Entity
 */
class ReportDefinition extends baseReportDefinition  implements ReportDefinitionInterface
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
    
    
}