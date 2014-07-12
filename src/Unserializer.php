<?php

namespace AndyTruong\Common;

use ReflectionClass;
use RuntimeException;
use stdClass;

/**
 * Class to unserialize an object from arary/json.
 *
 * use AndyTruong\Common\Serializer;
 * $obj = new Something();
 * print_r((new Serializer())->toArray($obj));
 *
 * @see \AndyTruong\Common\TestCases\Services\SerializerTest
 */
class Unserializer
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
     * Set property.
     *
     * @param string $pty
     * @param mixed $value
     * @throws \RuntimeException
     */
    public function setPropertyValue($obj, $pty, $value)
    {
        $method = 'set' . $this->camelize($pty);
        $r_class = new ReflectionClass($obj);

        if ($r_class->hasMethod($method) && $r_class->getMethod($method)->isPublic()) {
            // object's property should be an other object, cast it here
            if (is_array($value)) {
                if ($type_hint = $r_class->getMethod($method)->getParameters()[0]->getClass()) {
                    $value = $this->fromArray($value, $type_hint->getName());
                }
            }

            // Inject value to object's property
            $obj->{$method}($value);
        }
        elseif ($r_class->hasProperty($pty) && $r_class->getProperty($pty)->isPublic()) {
            $this->{$pty} = $value;
        }
        else {
            throw new RuntimeException(sprintf('Object.%s is not writable.', $pty));
        }
    }

    /**
     * Convert array to object.
     *
     * @param array $array
     * @param string $class_name
     * @return stdClass
     */
    public function fromArray($array, $class_name)
    {
        $obj = new $class_name;

        foreach ($array as $pty => $value) {
            $this->setPropertyValue($obj, $pty, $value);
        }

        return $obj;
    }

    /**
     * Convert json string to object.
     * 
     * @param string $json
     * @param string $class_name
     */
    public function fromJSON($json, $class_name)
    {
        return $this->fromArray(json_decode($json), $class_name);
    }

}
