<?php

namespace Earls\RhinoReportBundle\Templating\ExportConfigurator;

/**
 * Earls\RhinoReportBundle\Templating\ExportConfigurator\HtmlExportConfigurator.
 */
class HtmlExportConfigurator implements ExportConfigurator
{
    protected $attr = array();
    protected $response = array();

    public function __construct()
    {
        $this->response = $this->getDefaultResponse();
    }

    public function setAttr(array $attr)
    {
        $this->attr = $attr;

        return $this;
    }

    public function getAttr()
    {
        return $this->attr;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = array_merge($this->getDefaultResponse(), $response);

        return $this;
    }

    protected function getDefaultResponse()
    {
        return array(
            'uniqueBlock' => true,
        );
    }
}
