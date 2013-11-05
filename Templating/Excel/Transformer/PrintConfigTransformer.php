<?php

namespace Fuller\ReportBundle\Templating\Excel\Transformer;

use Symfony\Component\DependencyInjection\Container;

/**
 * Help on http://msdn.microsoft.com/en-us/library/aa140066.aspx or http://en.wikipedia.org/wiki/Microsoft_Office_XML_formats
 * Or just open Excel file and save it in format xml and open file.xml with text editor :-)
 *
 * Fuller\ReportBundle\Templating\Excel\Transformer\PrintConfigTransformer
 */
class PrintConfigTransformer
{

    protected $config;
    protected $availableConfig = array(
        'orientation',
        'margin',
        'scaling',
        'print_titles'
    );

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function transform()
    {
        foreach ($this->config as $key => $value) {
            if (in_array($key, $this->availableConfig)) {
                $this->config[$key] = $this->{(string) Container::camelize($key) . 'Transformer'}($value);
            } else {
                throw new \Exception("This printConfig value '$key' does'n exist");
            }
        }

        return $this->config;
    }

    public function orientationTransformer($config)
    {
        $availableValue = array('landscape', 'portrait');

        if (in_array($config, $availableValue)) {
            return ucfirst(strtolower($config));
        } else {
            throw new \Exception("This printConfig value for orientation '$config' is not valid");
        }
    }

    public function marginTransformer($config)
    {
        $availableValue = array('narrow', 'normal', 'wide');

        if (is_array($config)) {
            $availableValueForMargin = array('top', 'right', 'bottom', 'left');
            foreach ($config as $key => $value) {
                if (in_array($key, $availableValueForMargin)) {
                    $config[$key] = intval($value);
                } else {
                    throw new \Exception("This printConfig value for margin '$key' is not valid");
                }
            }
        } else {
            if (in_array($config, $availableValue)) {
                $shortCut = array(
                    'narrow' => array('top' => 0.75, 'right' => 0.25, 'bottom' => 0.75, 'left' => 0.25),
                    'normal' => array('top' => 0.75, 'right' => 0.7, 'bottom' => 0.75, 'left' => 0.7),
                    'wide' => array('top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1)
                );

                return $shortCut[$config];
            } else {
                throw new \Exception("This printConfig value for margin '$config' is not valid");
            }
        }
    }

    public function scalingTransformer($config)
    {
        $availableValue = array('column-in-one-page');
        if (in_array($config, $availableValue)) {
            return true;
        } else {
            throw new \Exception("This printConfig value for scaling '$config' is not valid");
        }
    }

    public function printTitlesTransformer($config)
    {
        if ($config) {
            return true;
        } else {
            throw new \Exception("This printConfig value for print_titles '$config' is not valid");
        }
    }

}
