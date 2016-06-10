<?php

namespace Earls\RhinoReportBundle\Report\Filter;

use Doctrine\DBAL\Statement;

/*
 *  Earls\RhinoReportBundle\Report\Filter\FilterTransformer
 *
 */

class FilterTransformer
{
    public function transform($type, $field)
    {
        $type = 'Earls\RhinoReportBundle\Report\Filter\FilterTransformer\\'.ucfirst($type).'FilterTransformer';
        $filterTransformerType = new $type();

        return $filterTransformerType->applyFilterTransformer($field);
    }

    public function bindArrayValue($req, $array, $typeArray = false)
    {
        if (is_object($req) && ($req instanceof Statement)) {
            foreach ($array as $key => $value) {
                if ($typeArray) {
                    $req->bindValue(":$key", $value, $typeArray[$key]);
                } else {
                    if (is_int($value)) {
                        $param = \PDO::PARAM_INT;
                    } elseif (is_bool($value)) {
                        $param = \PDO::PARAM_BOOL;
                    } elseif (is_null($value)) {
                        $param = \PDO::PARAM_NULL;
                    } elseif (is_string($value)) {
                        $param = \PDO::PARAM_STR;
                    } else {
                        $param = false;
                    }

                    if ($param) {
                        $req->bindValue(":$key", $value, $param);
                    }
                }
            }
        }

        return $req;
    }
}
