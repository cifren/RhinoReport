<?php

namespace Earls\RhinoReportBundle\Templating\Excel\Translator;

/**
 * Earls\RhinoReportBundle\Templating\Excel\Translator\CssXmlStyleTranslator
 */
class TagHeaderFooterTranslator
{

    /**
     *
     * @var string  The created string always start with this characters 
     */
    protected $start = '&L';

    /**
     *
     * @var array   Match list
     */
    protected $dictionnary = array(
        '{{page}}' => '&P',
        '{{pages}}' => '&N',
        '{{date}}' => '&D',
        '{{time}}' => '&T',
        '{{path}}' => '&Z',
        '{{file}}' => '&F',
        '{{sheet}}' => '&A',
    );

    public function __construct()
    {
        $this->lineBreak = chr(13) . chr(10);
    }

    /**
     * You will give a string which contains {{variable}} and the system will
     * replace them by the correct xml values
     * 
     * @param string $str   string you want to convert
     * @return string
     */
    public function translate($str)
    {
        $replace = array_keys($this->dictionnary);
        $subject = array_values($this->dictionnary);
        $xmlString = $this->start . str_replace($replace, $subject, $str);

        return $xmlString;
    }

}
