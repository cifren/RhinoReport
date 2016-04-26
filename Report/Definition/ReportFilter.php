<?php

namespace Earls\RhinoReportBundle\Report\Definition;

use Doctrine\ORM\Mapping as ORM;

/**
 * Earls\RhinoReportBundle\Report\Definition\ReportFilter
 */
class ReportFilter
{
    /**
     * @var string $name
     * 
     **/ 
    protected $name;
    
    /**
     * @var string $type
     **/ 
    protected $type;
    
    /**
     * @var array $options
     **/ 
    protected $options;
    
    /**
     * @var ReportDefinition
     */
    protected $parent;
    
    public function __construct($parent, $name, $type, $options = array()){
      $this->setName($name);
      $this->setType($type);
      $this->setOptions($options);
      $this->setParent($parent);
    }
    
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setType($type){
        $this->type = $type;
        return $this;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function setOptions($options){
        $this->options = $options;
        return $this;
    }
    
    public function getOptions(){
        return $this->options;
    }
    
    public function setParent($parent){
        $this->parent = $parent;
        return $this;
    }
    
    public function getParent(){
        return $this->parent;
    }
}