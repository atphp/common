<?php

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
