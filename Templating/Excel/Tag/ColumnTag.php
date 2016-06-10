<?php

namespace Earls\RhinoReportBundle\Templating\Excel\Tag;

/**
 * Earls\RhinoReportBundle\Templating\Excel\Tag\ColumnTag.
 *
 * http://msdn.microsoft.com/en-us/library/aa140066.aspx#odc_xmlss_ss:column
 */
class ColumnTag
{
    protected $caption;
    protected $autoFitWidth;
    protected $hidden;
    protected $index;
    protected $span;
    protected $styleID;
    protected $width;

    public function addNewOption($name, $value)
    {
        if (!array_key_exists($name, $this->getOptions())) {
            $options = implode(', ', array_keys($this->getOptions()));
            throw new \Exception('Column option `'.$name.'´ with value `'.$value.'´ is not an possible option, list of possible options '.$options);
        }
        $name = explode(':', $name)[1];
        $this->{'set'.ucfirst($name).'Option'}($value);

        return $this;
    }

    public function getOptions()
    {
        return array(
            'c:Caption' => $this->caption,
            'ss:AutoFitWidth' => $this->autoFitWidth,
            'ss:Hidden' => $this->hidden,
            'ss:Index' => $this->index,
            'ss:Span' => $this->span,
            'ss:StyleID' => $this->styleID,
            'ss:Width' => $this->width,
        );
    }

    private function setCaptionOption($value)
    {
        $this->caption = $value;
    }

    private function setAutoFitWidthOption($value)
    {
        $this->autoFitWidth = $value;
    }

    private function setHiddenOption($value)
    {
        $this->hidden = $value;
    }

    private function setIndexOption($value)
    {
        $this->index = $value;
    }

    private function setSpanOption($value)
    {
        $this->span = $value;
    }

    private function setStyleIDOption($value)
    {
        $this->styleID = $value;
    }

    private function setWidthOption($value)
    {
        $this->width = $value;
    }
}
