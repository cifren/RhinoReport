<?php

namespace Earls\RhinoReportBundle\Report\Templating\DefaultTemplate;

use Earls\RhinoReportBundle\Report\ReportObject\Report;
use Earls\RhinoReportBundle\Report\Templating\SystemTemplate\Model\Template;
use Earls\RhinoReportBundle\Report\ReportObject\Filter;

/**
 * Earls\RhinoReportBundle\Report\Templating\DefaultTemplate\ReportTemplateGeneratorManager
 */
class ReportTemplateGeneratorManager
{

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
     * @param  ModuleObject|Filter $object   object will be managed by the service
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
            $type = $this->getReportTypeName(get_class($object));
            $templateName = $object->getOptions()['template'];
            $templateGenerator = $this->getTemplateGenerator($templateName, $type, 'html');
            
            return $templateGenerator->getResponse($nameFile, $object, $arg, 'filter');
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
        $type = $this->getReportTypeName(get_class($item));
        $templateName = $item->getTemplate();
        $templateGenerator = $this->getTemplateGenerator($templateName, $type, $format);

        return $templateGenerator->getResponse($nameFile, $item, $arg);
    }

    protected function getReportTypeName($className, $id = null)
    {
        $reportType = $this->container->getParameter('report_type');

        $type = array();
        foreach ($reportType as $key => $parameter) {
            $type[$parameter['class']] = $key;
        }

        if (!key_exists($className, $type)) {
            throw new \UnexpectedValueException("The type of class '$className' is not valid for item with id '$id' and type '$type', check your services");
        }

        return $type[$className];
    }

    public function getTemplating(Report $object, $remoteUrl, $exportUrl)
    {
        $template = new Template();

        $template->setFilter($object->getFilter());
        $template->setRemoteUrl($remoteUrl);
        $template->setOptions($object->getOptions());

        foreach ($object->getItems() as $item) {
            $type = $this->getReportTypeName(get_class($item));
            $templateName = $item->getTemplate();
            $templateGenerator = $this->getTemplateGenerator($templateName, $type, 'html');
            $templating = $templateGenerator->getTemplating($item, $remoteUrl, $exportUrl);
            $template->addModule($templating);
            $template->addUniqueBlockName($templateGenerator->getUniqueBlockName());
        }
        
        return $template;
    }

    public function getData(Report $object)
    {
        $data = array();
        foreach ($object->getItems() as $key => $item) {
            $type = $this->getReportTypeName(get_class($item));
            $templateName = $item->getTemplate();
            $templateGenerator = $this->getTemplateGenerator($templateName, $type, 'html');
            $data[$key] = $templateGenerator->getData($item);
        }

        return $data;
    }

    public function getTemplateGenerator($templateName, $moduleType, $format)
    {
        try {
            $configTemplate = $this->container->getParameter("$moduleType.$templateName");
        } catch (\Exception $e) {
            throw new \Exception(sprintf('The template name "%s" doesn\'t exist for the module %s', $templateName, ucfirst($moduleType)));
        }
        $serviceName = $configTemplate['generator.service'][$format];

        return $this->container->get($serviceName);
    }

}
