<?php

namespace AndyTruong\Common;

class NullObject
{

    public $called = [];
    public static $static_called = array();

    public function __call($name, $arguments)
    {
        $this->called[$name][] = $arguments;
    }

    public static function __callStatic($name, $arguments)
    {
        static::$static_called[$name][] = $arguments;
    }

}
