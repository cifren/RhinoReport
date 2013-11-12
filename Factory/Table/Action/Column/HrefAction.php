<?php

namespace Earls\RhinoReportBundle\Factory\Table\Action\Column;

use Earls\RhinoReportBundle\Factory\Table\Action\Column\Action;

/*
 *  Earls\RhinoReportBundle\Factory\Table\Action\Column\HrefAction
 *
 */

class HrefAction extends Action
{

    protected $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function setData()
    {
        $routeParameters = array();
        // Link the routing name, e.g. storeid with the data storeId
        foreach ($this->options['arg_dataIds'] as $key => $columnName) {
            $routeParameters[$key] = $this->rowData[$columnName];
        }
        // Link the routing name with the display to screen data, e.g. /{packsize}
        foreach ($this->options['arg_displayIds'] as $key => $columnName) {
            $routeParameters[$key] = $this->rowObject[$columnName];
        }
        // Link the routing name with the display to screen data, e.g. /{packsize}
        $routeParameters = array_merge($routeParameters, $this->options['custom_data']);
        $route = $this->router->generate($this->options['route'], $routeParameters, $this->options['absolute']);

        $ahref = '<a href="' . $route . '">' . $this->column->getData() . '</a>';
        return $ahref;
    }

    public function getOptions()
    {
        return array(
            'route' => null,
            'parameters' => array(),
            'absolute' => false,
            'arg_dataIds' => array(),
            'arg_displayIds' => array(),
            'custom_data' => array()
        );
    }

}
