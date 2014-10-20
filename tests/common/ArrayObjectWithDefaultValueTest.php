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

use AndyTruong\Common\ArrayObjectWithDefaultValue;
use PHPUnit_Framework_TestCase;

class ArrayObjectWithDefaultValueTest extends PHPUnit_Framework_TestCase
{

    public function testDefaultValue()
    {
        $obj = (new ArrayObjectWithDefaultValue())->setDefaultValue('default');
        $this->assertTrue($obj->offsetUseDefault('foo'));
        $this->assertTrue(isset($obj['foo']));
        $this->assertEquals('default', $obj['foo']);
    }

}
