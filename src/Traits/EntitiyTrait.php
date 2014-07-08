<?php

namespace AndyTruong\Common\Traits;

use ReflectionClass;

trait EntitiyTrait
{

    /**
     * Camelizes a given string.
     *
     * @param  string $string Some string
     *
     * @return string The camelized version of the string
     */
    private function camelize($string)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $string);
    }

    /**
     * Get a property in object.
     *
     * @param string $pty
     * @return mixed
     */
    protected function getPropertyValue($pty, $include_null)
    {
        $return = $this->{$pty};

        $camelPty = $this->camelize($pty);
        $r_class = new ReflectionClass($this);
        foreach (array('get', 'is', 'has') as $prefix) {
            $method = $prefix . $camelPty;
            if ($r_class->hasMethod($method) && $r_class->getMethod($method)->isPublic()) {
                $return = $this->{$method}();
            }
        }

        if (is_object($return) && \method_exists($return, 'toArray')) {
            $return = $return->toArray($include_null);
        }

        return $return;
    }

    /**
     * Set property.
     *
     * @param string $pty
     * @param mixed $value
     * @throws \RuntimeException
     */
    public function setPropertyValue($pty, $value)
    {
        $method = 'set' . $this->camelize($pty);
        $r_class = new ReflectionClass($this);

        if ($r_class->hasMethod($method) && $r_class->getMethod($method)->isPublic()) {
            if (is_array($value) && $type_hint = $r_class->getMethod($method)->getParameters()[0]->getClass()) {
                if (method_exists($type_hint->getName(), 'fromArray')) {
                    $value = call_user_func([$type_hint->getName(), 'fromArray'], $value);
                }
            }
            $this->{$method}($value);
        }
        elseif ($r_class->hasProperty($pty) && $r_class->getProperty($pty)->isPublic()) {
            $this->{$pty} = $value;
        }
        else {
            throw new \RuntimeException(sprintf('Object.%s is not writable.', $pty));
        }
    }

    /**
     * Represent object as array.
     *
     * @param boolean $include_null
     * @return array
     */
    public function toArray($include_null = false)
    {
        $array = array();

        foreach ((new ReflectionClass($this))->getProperties() as $pty) {
            /* @var $pty \ReflectionProperty */
            $value = $this->getPropertyValue($pty->getName(), $include_null);
            if ((null !== $value) || (null === $value && $include_null)) {
                $array[$pty->getName()] = $value;
            }
        }

        return $array;
    }

    /**
     * Simple fromArray factory.
     *
     * @param array $input
     * @return static
     */
    public static function fromArray($input)
    {
        $me = new static();

        foreach ($input as $pty => $value) {
            $me->setPropertyValue($pty, $value);
        }

        return $me;
    }

    /**
     * Represent object in json format.
     *
     * @param boolean $include_null
     * @param int $options
     * @return string
     */
    public function toJSON($include_null = false, $options = 0)
    {
        return json_encode($this->toArray($include_null), $options);
    }

    /**
     * Create new object from json string.
     *
     * @param string $input
     * @return static
     */
    public static function fromJSON($input)
    {
        return static::fromArray(json_decode($input, true));
    }

}
