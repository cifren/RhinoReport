<?php

namespace Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Simplifier;

use Earls\RhinoReportBundle\Module\Table\TableObject\TableObject;

/**
 * Earls\RhinoReportBundle\Module\Table\Templating\DataTableTemplate\Simplifier\ModuleObjectSimplifier.
 */
class ModuleObjectSimplifier
{
    public function getSimplifyObject(TableObject $tableObject)
    {
        $array = array();
        $array['object'] = $tableObject;
        $array['attr'] = $this->formatAttributes($tableObject->getAttributes(), array('class' => 'dataTable', 'width' => '100%', 'cellspacing' => 0));
        $array['id'] = $tableObject->getId();

        return $array;
    }

    protected function formatAttributes(array $attr = null, $defaultClass = array())
    {
        //convert array to string
        if (isset($attr['class'])) {
            if (isset($defaultClass['class']) && !empty($defaultClass['class'])) {
                $attr['class'] = array_merge($attr['class'], $defaultClass['class']);
            }
            $attr['class'] = implode(' ', $attr['class']);
        }
        //convert array to string html
        if (isset($attr['style'])) {
            $attr['style'] = $this->getAttrStyle($attr['style']);
        }

        foreach ($defaultClass as $key => $item) {
            if (($key != 'class' || $key != 'style') && !isset($attr[$key])) {
                $attr[$key] = $item;
            }
        }

        return $attr;
    }

    /**
     * Convert array of style into string for html render.
     *
     * @param array $style
     *
     * @return string
     */
    protected function getAttrStyle(array $style)
    {
        $styleString[] = null;
        foreach ($style as $key => $value) {
            $styleString[] .= $key.':'.$value.';';
        }

        return implode('', $styleString);
    }
}
