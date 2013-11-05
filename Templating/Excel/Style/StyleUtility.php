<?php

namespace Fuller\ReportBundle\Templating\Excel\Style;

/**
 * Fuller\ReportBundle\Templating\Excel\Style\StyleUtility
 */
class StyleUtility
{

    static public function parseStyle($style)
    {
        foreach ($style as $keyClass => $rules) {
            $classes = explode(',', $keyClass);
            if (count($classes) <= 1) {
                continue;
            }
            unset($style[$keyClass]);
            foreach ($classes as $class) {
                $style[str_replace(' ', '', trim($class))] = $rules;
            }
        }
        return $style;
    }

}