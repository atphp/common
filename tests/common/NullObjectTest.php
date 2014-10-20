<?php

/**
 * This file is part of AT Common package.
 *
 * (c) 2014-2014 thehongtt@gmail.com
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
        $this->assertEquals(array(1, 2), $null->called['callMethod'][0]);
    }

    public function testCallStaticMethod()
    {
        $null = $this->getNullObject();
        $this->assertNull($null::callNullMethod(3, 4));
        $this->assertCount(1, $null::$staticCalled['callNullMethod']);
        $this->assertEquals(array(3, 4), $null::$staticCalled['callNullMethod'][0]);
    }

}
