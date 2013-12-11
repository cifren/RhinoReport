<?php

namespace Earls\RhinoReportBundle\Templating\Excel\Style;

/**
 * Earls\RhinoReportBundle\Templating\Excel\Style\StyleUtility
 */
class StyleUtility
{

    public static function parseStyle($style)
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
