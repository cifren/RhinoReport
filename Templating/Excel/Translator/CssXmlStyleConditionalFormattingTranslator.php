<?php

namespace Earls\RhinoReportBundle\Templating\Excel\Translator;

use Earls\RhinoReportBundle\Templating\Excel\Style\Style;

/**
 * 
 * Earls\RhinoReportBundle\Templating\Excel\Translator\CssXmlStyleConditionalFormattingTranslator
 */
class CssXmlStyleConditionalFormattingTranslator
{

    /** Example :
     *      - 'border-top-style' value css
     *      - 'borderStyle' function executed
     *      - 'top' argument for the function
     */
    protected $dictionnary = array(
        'color' => 'fontColor',
        'font-color' => 'fontColor',
        'background-color' => 'backgrounColor',
    );
    protected $styleArray = array();

    public function translate(array $classes)
    {
        foreach ($classes as $class => $rules) {
            foreach ($rules as $rule => $value) {
                if (isset($this->dictionnary[$rule])) {
                    $this->styleArray[$class] = $this->{$this->dictionnary[$rule]}($rule, $value);
                }
            };
        }
        //var_dump($this->styleArray);
        return $this->styleArray;
    }

    protected function fontColor($ruleName, $value = NULL)
    {
        if ($value == NULL) {
            throw new \Exception('Value css can\'t be translated in `font-color´');
        }
        $newRuleName = 'color';

        return array($newRuleName => $value);
    }

    protected function backgrounColor($ruleName, $value = NULL)
    {
        if ($value == NULL) {
            throw new \Exception('Value css can\'t be translated in `font-color´');
        }
        $newRuleName = 'background';

        return array($newRuleName => $value);
    }

}
