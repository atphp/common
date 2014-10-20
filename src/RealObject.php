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

use DomainException;
use Iterator;

/**
 * Enforces stricter object semantics than PHP.
 *
 *  // problem
 *  $myObj = new MyClass();
 *  $myObj->iDontWantThis = 'too badd!';
 *
 *  // Fix it
 *  class MyClass extends RealObject {}
 *  (new MyClass)->iDontWantThis = 'throw DomainException!';
 *
 * The object based on this class will not allow any iterator if it's not really
 * implemented.
 */
abstract class RealObject implements Iterator
{

    public function __set($name, $value)
    {
        throw new DomainException(sprintf(
            'Attempt to write to undeclared property %s::%s.', get_class($this), $name
        ));
    }

    public function current()
    {
        $this->throwOnAttemptedIteration();
    }

    public function key()
    {
        $this->throwOnAttemptedIteration();
    }

    public function next()
    {
        $this->throwOnAttemptedIteration();
    }

    public function rewind()
    {
        $this->throwOnAttemptedIteration();
    }

    public function valid()
    {
        $this->throwOnAttemptedIteration();
    }

    private function throwOnAttemptedIteration()
    {

        throw new DomainException(sprintf(
            'Attempting to iterate an object (of class %s) which is not iterable.', get_class($this)
        ));
    }

}
