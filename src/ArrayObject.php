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

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Many case we need array features for our objects, to avoid duplicating code,
 * just use this class.
 *
 * @todo Missing test case.
 */
class ArrayObject extends RealObject implements Countable, ArrayAccess, Iterator
{

    protected $data = array();

    public function __construct(array $initialValue = array())
    {
        $this->data = $initialValue;
    }

    public function toArray()
    {
        return array_map(function($item) {
            return $item instanceof ArrayObject ? $item->toArray() : $item;
        }, iterator_to_array($this, true));
    }

    public function count()
    {
        return count($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function valid()
    {
        return (key($this->data) !== null);
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function offsetGet($key)
    {
        return $this->data[$key];
    }

    public function offsetSet($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }

}
