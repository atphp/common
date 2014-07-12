<?php

namespace AndyTruong\Common;

use ReflectionClass;
use ReflectionProperty;
use stdClass;

/**
 * Class to serialize an object to arary/json.
 *
 * use AndyTruong\Common\Serializer;
 * $obj = new Something();
 * print_r((new Serializer())->toArray($obj));
 *
 * @see \AndyTruong\Common\TestCases\Services\SerializerTest
 */
class Serializer
{

    /**
     * Camelizes a given string.
     *
     * @param  string $string Some string
     *
     * @return string The camelized version of the string
     */
    protected function camelize($string)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $string);
    }

    /**
     * Get value of an object using has|is|get method.
     *
     * @param stdClass $obj
     * @param string $pty
     * @param boolean $include_null
     * @return mixed
     */
    protected function getPropertyValue($obj, $pty, $include_null)
    {
        $return = null;

        $camelPty = $this->camelize($pty);
        $r_class = new ReflectionClass($obj);

        // property is public
        if (property_exists($obj, $pty) && $r_class->getProperty($pty)->isPublic()) {
            $return = $obj->{$pty};
        }
        else {
            foreach (array('get', 'is', 'has') as $prefix) {
                $method = $prefix . $camelPty;
                if ($r_class->hasMethod($method) && $r_class->getMethod($method)->isPublic()) {
                    $return = $obj->{$method}();
                }
            }
        }

        if (is_object($return)) {
            $return = $this->toArray($return, $include_null);
        }

        return $return;
    }

    /**
     * Convert object to array.
     *
     * @param stdClass $obj
     * @param bool $include_null
     * @return array
     */
    public function toArray($obj, $include_null = false)
    {
        $array = array();

        foreach ((new ReflectionClass($obj))->getProperties() as $pty) {
            /* @var $pty ReflectionProperty */
            $value = $this->getPropertyValue($obj, $pty->getName(), $include_null);
            if ((null !== $value) || (null === $value && $include_null)) {
                $array[$pty->getName()] = $value;
            }
        }

        return $array;
    }

    /**
     * Represent object in json format.
     *
     * @param stdClass $obj
     * @param bool $include_null
     * @return string
     */
    public function toJSON($obj, $include_null = false)
    {
        return json_encode($this->toArray($obj, $include_null));
    }

}
