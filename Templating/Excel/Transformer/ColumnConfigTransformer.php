<?php

namespace Fuller\ReportBundle\Templating\Excel\Transformer;

use Fuller\ReportBundle\Templating\Excel\Tag\ColumnTag;

/**
 * Help on http://msdn.microsoft.com/en-us/library/aa140066.aspx or http://en.wikipedia.org/wiki/Microsoft_Office_XML_formats
 *
 * Fuller\ReportBundle\Templating\Excel\Transformer\ColumnConfigTransformer
 */
class ColumnConfigTransformer
{

    protected $config;
    protected $columnTag;
    protected $availableConfig = array(
        'width',
        'hidden'
    );

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function transform()
    {
        foreach ($this->config as $column => $columnConfig) {
            $this->columnTag = new ColumnTag();
            //column name
            $this->columnTag->addNewOption('ss:Index', $column);
            foreach ($columnConfig as $key => $value) {
                if (in_array($key, $this->availableConfig)) {
                    $this->{$key . 'Transformer'}($value);
                } else {
                    throw new \Exception("This columnConfig value '$key' does'n exist");
                }
            }
            $this->config[$column] = $this->columnTag;
        }

        return $this->config;
    }

    public function widthTransformer($value)
    {
        if ((int) $value) {
            //multiply result because in config excel size should be in point => see XML : "Specifies the width of a column in points. This value must be greater than or equal to 0."
            $this->columnTag->addNewOption('ss:Width', $value * 5.442614514);
        } else {
            throw new \Exception("This columnConfig value for width '$value' is not valid");
        }
    }

    public function hiddenTransformer($value)
    {
        if ($value == true) {
            $this->columnTag->addNewOption('ss:Hidden', 1);
        }
    }

}
