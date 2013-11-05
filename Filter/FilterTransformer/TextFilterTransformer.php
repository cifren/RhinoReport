<?php

namespace Fuller\ReportBundle\Filter\FilterTransformer;

/**
 * Used for lexik bundle in order to apply a pattern on the string
 *
 *  Fuller\ReportBundle\Filter\FilterTransformer\TextFilterTransformer
 *
 */
class TextFilterTransformer
{

    const PATTERN_EQUALS = '%s';
    const PATTERN_START_WITH = '%s%%';
    const PATTERN_END_WITH = '%%%s';
    const PATTERN_CONTAINS = '%%%s%%';

    public function applyFilterTransformer($field)
    {
        $transformField = null;

        if (!empty($field['text'])) {
            $transformField = array();
            $transformField['condition'] = ($field['condition_pattern'] == self::PATTERN_EQUALS) ? '=' : 'LIKE';

            $value = sprintf($field['condition_pattern'], $field['text']);

            $transformField['value'] = $value;
        }

        return $transformField;
    }

}
