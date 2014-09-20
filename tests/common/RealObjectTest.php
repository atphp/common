<?php

namespace AndyTruong\Common\TestCases;

use AndyTruong\Common\Fixtures\DemoRealObject;
use PHPUnit_Framework_TestCase;

class RealObjectTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException DomainException
     * @expectedExceptionMessage Attempt to write to undeclared property AndyTruong\Common\Fixtures\DemoRealObject::foo.
     */
    public function testStrictMethod()
    {
        $obj = new DemoRealObject();
        $obj->foo = 'bar';
    }

    /**
     * @expectedException DomainException
     * @expectedExceptionMessage Attempting to iterate an object (of class AndyTruong\Common\Fixtures\DemoRealObject) which is not iterable.
     */
    public function testStrictInterator()
    {
        $obj = new DemoRealObject();
        foreach ($obj as $k => $v) {

        }
    }

}
