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

    protected function getPropertyValue($pty)
    {
        $return = $this->{$pty};

        $camelPty = $this->camelize($pty);
        $r_class = new ReflectionClass($this);
        foreach (['get', 'is', 'has'] as $prefix) {
            $method = $prefix . $camelPty;
            if ($r_class->hasMethod($method) && $r_class->getMethod($method)->isPublic()) {
                $return = $this->{$method}();
            }
        }

        if (is_object($return) && \method_exists($return, 'toArray')) {
            $return = $return->toArray();
        }

        return $return;
    }

    public function setPropertyValue($pty, $value)
    {
        $method = 'set' . $this->camelize($pty);
        $r_class = new ReflectionClass($this);

        if ($r_class->hasMethod($method) && $r_class->getMethod($method)->isPublic()) {
            $this->{$method}($value);
        }
        elseif ($r_class->hasProperty($pty) && $r_class->getProperty($pty)->isPublic()) {
            $this->{$pty} = $value;
        }
        else {
            throw new \RuntimeException(sprintf('Object.%s is not writable.', $pty));
        }
    }

    public function toArray()
    {
        $array = [];

        foreach ((new ReflectionClass($this))->getProperties() as $pty) {
            /* @var $pty \ReflectionProperty */
            $array[$pty->getName()] = $this->getPropertyValue($pty->getName());
        }

        return $array;
    }

    public static function fromArray($input)
    {
        $me = new static();

        foreach ($input as $pty => $value) {
            $me->setPropertyValue($pty, $value);
        }

        return $me;
    }

}
