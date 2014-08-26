Usage
=====

You can display from your report a raw version of your data coming directly from "getArrayData" from your config

Just add in the url "rhino-data-debug=true" if your array is a simple array

For more complex array from the function "getArrayData", example :

``` php
public function getArrayData(array $data, $dataFilter)
{
    $array['tableReason'] = $this->getReasonArray($dataFilter);

    $array['tableSales'] = $this->getSalesArray($dataFilter);

    return $array;
}
``` 

The parameter will be "rhino-data-debug=tableReason" for the selected array.