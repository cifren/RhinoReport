<?php

namespace Earls\RhinoReportBundle\Model\Helper;

class ArrayHelper
{

    /**
     * create an html table from an array, the array has to have rows and always the same ids for all lines and only simple string or number
     * 
     * @param array $array
     * @return string
     */
    public function displayArrayDebug(array $array)
    {
        $display = "<div>Count items : ". count($array);
        $display .= "<table border='1' style='border-collapse:collapse;border-color: silver;'>";
        $display.="<thead><tr>";
        reset($array);
        $first_key = key($array);
        foreach ($array[$first_key] as $key => $value) {
            $display.="<th>";
            $display.="$key";
            $display.="</th>";
        }
        $display.="</tr></thead>";
        $display.="<tbody>";
        foreach ($array as $values) {
            $display.="<tr>";
            foreach ($values as $value) {
                $display .= "<td>";
                $display .= $value;
                $display .= "</td>";
            }
            $display.="</tr>";
        }
        $display.="</tbody>";

        return $display;
    }

}
