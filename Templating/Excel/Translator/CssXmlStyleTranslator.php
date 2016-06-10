<?php

namespace Earls\RhinoReportBundle\Templating\Excel\Translator;

use Earls\RhinoReportBundle\Templating\Excel\Style\Style;

/**
 * Help on http://msdn.microsoft.com/en-us/library/aa140066.aspx or http://en.wikipedia.org/wiki/Microsoft_Office_XML_formats.
 *
 * Earls\RhinoReportBundle\Templating\Excel\Translator\CssXmlStyleTranslator
 */
class CssXmlStyleTranslator
{
    /* Example :
     *      - 'border-top-style' value css
     *      - 'borderStyle' function executed
     *      - 'top' argument for the function
     */

    protected $dictionnary = array(
        'font-weight' => array('fontWeight'),
        'font-size' => array('fontSize'),
        'background-color' => array('backgroundColor'),
        'number-format-code' => array('numberformatcode'),
        'text-indent' => array('textindent'),
        'text-align' => array('hAlign'),
        'vertical-align' => array('vAlign'),
        'border-style' => array('borderStyle'),
        'border-top-style' => array('borderStyle', array('top')),
        'border-bottom-style' => array('borderStyle', array('bottom')),
        'border-left-style' => array('borderStyle', array('left')),
        'border-right-style' => array('borderStyle', array('right')),
        'border-width' => array('borderWidth'),
        'border-top-width' => array('borderWidth', array('top')),
        'border-bottom-width' => array('borderWidth', array('bottom')),
        'border-left-width' => array('borderWidth', array('left')),
        'border-right-width' => array('borderWidth', array('right')),
        'wrap-text' => array('wrapText'),
        'font-color' => array('fontColor'),
        'protection' => array('protection'),
    );
    protected $styleArray = array();

    public function translate(array $strCss)
    {
        foreach ($strCss as $class => $rules) {
            $style = new Style();
            $this->styleArray[$class] = $style;
            foreach ($rules as $rule => $value) {
                //add parent for style
                if ($rule == 'parent') {
                    $style->setParent($value);
                    continue;
                }
                if (isset($this->dictionnary[$rule])) {
                    if (!isset($this->dictionnary[$rule][1])) {
                        $this->dictionnary[$rule][1] = null;
                    }
                    $this->styleArray[$class] = $this->{$this->dictionnary[$rule][0]}($style, $value, $this->dictionnary[$rule][1]);
                } /* else {
                  throw new \Exception('Rule css `' . $rule . '´ can\'t be translated');
                  } */
            }
        }

        return $this->styleArray;
    }

    protected function fontWeight($style, $value)
    {
        switch (true) {
            case is_int($value):
                if ($value >= 600) { //normal
                    $xmlValue = 0;
                } elseif ($value < 600) { //bold
                    $xmlValue = 1;
                }

                break;
            case $value === 'bold':
            case $value === 'bolder':
                $xmlValue = 1;
                break;
            case $value === 'normal':
            case $value === 'lighter':
                $xmlValue = 0;
                break;

            default:
                throw new \Exception('Value css `'.$value.'´ can\'t be translated in `font-weight´');
                break;
        }

        return $style->addNewRule('font', 'ss:Bold', $xmlValue);
    }

    protected function fontSize($style, $value)
    {
        if (is_numeric($value)) {
            $xmlValue = $value;
        } else {
            throw new \Exception('Value css `'.$value.'´ can\'t be translated in `font-size´');
        }

        return $style->addNewRule('font', 'ss:Size', $xmlValue);
    }

    protected function fontColor($style, $value = null)
    {
        if ($value == null) {
            throw new \Exception('Value css can\'t be translated in `font-color´');
        }

        return $style->addNewRule('font', 'ss:Color', $value);
    }

    protected function backgroundColor($style, $value)
    {
        switch (true) {
            case preg_match('/(#[0-9a-fA-F]{6})/', $value):
                $xmlValue = $value;
                break;
            default:
                throw new \Exception('Value css `'.$value.'´ can\'t be translated in `background-color´');
                break;
        }

        $style->addNewRule('interior', 'ss:Pattern', 'Solid');
        $style->addNewRule('interior', 'ss:Color', $xmlValue);

        return $style;
    }

    protected function numberformatcode($style, $value)
    {
        $style->addNewRule('numberFormat', 'ss:Format', $value);

        return $style;
    }

    protected function textindent($style, $value)
    {
        $style->addNewRule('alignment', 'ss:Indent', (float) (preg_replace("/[^-0-9\.]/", '', $value)) / 9);

        return $style;
    }

    protected function vAlign($style, $value)
    {
        switch ($value) {
            case 'top':
                $value = 'Top';
                break;
            case 'middle':
                $value = 'Center';
                break;
            case 'bottom':
                $value = 'Bottom';
                break;
            default:
                $value = 'top';
                break;
        }
        $style->addNewRule('alignment', 'ss:Vertical', $value);

        return $style;
    }

    protected function hAlign($style, $value)
    {
        switch ($value) {
            case 'left':
                $value = 'Left';
                break;
            case 'center':
                $value = 'Center';
                break;
            case 'right':
                $value = 'Right';
                break;
            default:
                $value = 'left';
                break;
        }
        $style->addNewRule('alignment', 'ss:Horizontal', $value);

        return $style;
    }

    protected function borderStyle($style, $value, $arg)
    {
        switch ($value) {
            case 'hidden':
                $value = 'none';
                break;
            case 'dotted':
                $value = 'Dot';
                break;
            case 'dashed':
                $value = 'Dash';
                break;
            case 'solid':
                $value = 'Continuous';
                break;
            case 'double':
                $value = 'Double';
                break;
            case 'DashDot':
                $value = 'DashDot';
                break;
            case 'DashDotDot':
                $value = 'DashDotDot';
                break;
            case 'SlantDashDot':
                $value = 'SlantDashDot';
                break;
            default:
                $value = 'Continuous';
                break;
        }
        if (empty($arg[0])) {
            $style->addNewRule('topBorder', 'ss:LineStyle', $value);
            $style->addNewRule('bottomBorder', 'ss:LineStyle', $value);
            $style->addNewRule('rightBorder', 'ss:LineStyle', $value);
            $style->addNewRule('leftBorder', 'ss:LineStyle', $value);
        } else {
            $style->addNewRule($arg[0].'Border', 'ss:LineStyle', $value);
        }

        return $style;
    }

    protected function borderWidth($style, $value, $arg)
    {
        switch ($value) {
            case 'none':
                $value = '0'; //Hairline
                break;
            case 'thin':
                $value = '1'; //Thin
                break;
            case 'medium':
                $value = '2'; //Medium
                break;
            case 'thick':
                $value = '3'; //Thick
                break;
            default:
                $value = '1';
                break;
        }

        if (empty($arg[0])) {
            $style->addNewRule('topBorder', 'ss:Weight', $value);
            $style->addNewRule('bottomBorder', 'ss:Weight', $value);
            $style->addNewRule('rightBorder', 'ss:Weight', $value);
            $style->addNewRule('leftBorder', 'ss:Weight', $value);
        } else {
            $style->addNewRule($arg[0].'Border', 'ss:Weight', $value);
        }

        return $style;
    }

    protected function wrapText($style, $value)
    {
        switch ($value) {
            case true:
                $value = true; //Medium
                break;
            default:
                $value = false;
                break;
        }
        $style->addNewRule('alignment', 'ss:WrapText', $value);

        return $style;
    }

    protected function protection($style, $value)
    {
        switch ($value) {
            case true:
                $value = '1'; //Medium
                break;
            default:
                $value = '0';
                break;
        }
        $style->addNewRule('protection', 'ss:Protected', $value);

        return $style;
    }
}
