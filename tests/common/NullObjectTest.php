<?php

namespace AndyTruong\Common\TestCases;

use AndyTruong\Common\NullObject;
use PHPUnit_Framework_TestCase;

/**
 * @group nullobject
 */
class NullObjectTest extends PHPUnit_Framework_TestCase
{

    private function getNullObject()
    {
        return new NullObject();
    }

    public function testCallMethod()
    {
        $null = $this->getNullObject();
        $this->assertNull($null->callMethod(1, 2));
        $this->assertCount(1, $null->called['callMethod']);
        $this->assertEquals([1, 2], $null->called['callMethod'][0]);
    }

    public function testCallStaticMethod()
    {
        $null = $this->getNullObject();
        $this->assertNull($null::callNullMethod(3, 4));
        $this->assertCount(1, $null::$static_called['callNullMethod']);
        $this->assertEquals([3, 4], $null::$static_called['callNullMethod'][0]);
    }

}
