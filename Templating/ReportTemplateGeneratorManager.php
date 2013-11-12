<?php

namespace Earls\RhinoReportBundle\Templating;

use Earls\RhinoReportBundle\Model\Report;

/**
 * Earls\RhinoReportBundle\Templating\ReportTemplateGeneratorManager
 */
class ReportTemplateGeneratorManager
{

    protected $report;
    protected $format;
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Return a response for the report, an ID need to be selected
     * Can return filter in HTML only
     *
     * @param  string $format   html, xls, pdf...
     * @param  string $nameFile name files for xls, pdf or other
     * @param  Report $object   object will be managed by the service
     * @param  string $id       id selected in report object
     * @param  array  $arg      arguments like with js or not, css or not....
     * @return string
     *
     * @throws \UnexpectedValueException
     */
    public function getResponse($format, $nameFile, Report $object, $id = null, $arg = array())
    {
        if (null == $id) {
            throw new \UnexpectedValueException('This feature is not available yet, you can\'t export all content');
        }

        //return only html for now, only for filter
        if ('filter' == $id && 'html' == $format) {
            return $this->container->get("report.template.generator.$format")->getResponse($nameFile, $object, $arg);
        } elseif ('filter' == $id && 'html' != $format) {
            throw new \UnexpectedValueException('This feature is not available yet, you can\'t export filter in other format as html');
        }

        $item = $object->getItem($id);
        $typeNameItem = $this->getReportTypeName(get_class($item), $id);

        $reportParameter = $this->container->getParameter("report_type");
        $validExport = $reportParameter[$typeNameItem]['export'];

        if (!in_array($format, $validExport)) {
            throw new \UnexpectedValueException("The format '$format' is not valid");
        }

        return $this->container->get("report.$typeNameItem.template.generator.$format")->getResponse($nameFile, $item, $arg);
    }

    protected function getReportTypeName($className, $id)
    {
        $reportType = $this->container->getParameter('report_type');

        $type = array();
        foreach ($reportType as $key => $parameter) {
            $type[$parameter['class']] = $key;
        }

        if (!key_exists($className, $type)) {
            throw new \UnexpectedValueException("The type of class '$className' is not valid for item with id '$id'");
        }

        return $type[$className];
    }

}
