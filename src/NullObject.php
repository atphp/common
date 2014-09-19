<?php

namespace AndyTruong\Common;

/**
 * A class responsible to all method calling, but do nothing, return nothing.
 */
class NullObject
{

    /** @var mixed[] */
    public $called = [];

    /** @var mixed[] */
    public static $staticCalled = array();

    public function __call($name, $arguments)
    {
        $this->called[$name][] = $arguments;
    }

    public static function __callStatic($name, $arguments)
    {
        static::$staticCalled[$name][] = $arguments;
    }

}
