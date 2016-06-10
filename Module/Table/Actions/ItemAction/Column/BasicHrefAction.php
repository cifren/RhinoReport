<?php

namespace Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column;

/**
 *  Earls\RhinoReportBundle\Module\Table\Actions\ItemAction\Column\BasicHrefAction.
 */
class BasicHrefAction extends Action
{
    protected $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function setData()
    {
        $routeParameters = array();
        $missingParam = false;
        // Link the routing name, e.g. storeid with the data storeId
        foreach ($this->options['arg_dataIds'] as $key => $columnName) {
            $routeParameters[$key] = $this->rowData[$columnName];
            if ($this->rowData[$columnName] === null) {
                $missingParam = true;
            }
        }
        // Link the routing name with the display to screen data, e.g. /{packsize}
        foreach ($this->options['arg_displayIds'] as $key => $columnName) {
            $routeParameters[$key] = $this->rowObject[$columnName];
            if ($this->rowObject[$columnName] === null) {
                $missingParam = true;
            }
        }
        //if one of the param are missing (=null), no data displayed
        if ($this->options['strictParameters'] && $missingParam) {
            return null;
        }

        // Link the routing name with the display to screen data, e.g. /{packsize}
        $fullParameters = array_merge($routeParameters, $this->options['custom_data']);
        $route = $this->getUrl($fullParameters);

        $attrConcat = array();
        foreach ($this->options['attr'] as $key => $value) {
            $attrConcat[] = "$key='$value'";
        }
        $attrString = implode(' ', $attrConcat);
        $ahref = "<a href='{$route}' {$attrString}>{$this->column->getData()}</a>";

        return $ahref;
    }

    protected function getUrl($parameters)
    {
        $params = array();
        foreach ($parameters as $key => $value) {
            $params[] = $key.'='.urlencode($value);
        }

        return $this->options['url'].'?'.implode('&', $params);
    }

    public function getOptions()
    {
        return array(
            'route' => null,
            'parameters' => array(),
            'absolute' => false,
            'arg_dataIds' => array(),
            'arg_displayIds' => array(),
            'custom_data' => array(),
            'attr' => array(),
            //if one param is missing action return null
            'strictParameters' => false,
        );
    }
}
