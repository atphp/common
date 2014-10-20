<?php

/**
 * This file is part of AT Common package.
 *
 * (c) 2014-2014 thehongtt@gmail.com
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
