<?php

namespace AndyTruong\Common\TestCases\Services;

use AndyTruong\Common\Context;
use DateTime;
use PHPUnit_Framework_TestCase;

class ContextTest extends PHPUnit_Framework_TestCase
{

    private function getContext()
    {
        return new Context();
    }

    public function testInfo()
    {
        $context = $this->getContext();
        $this->assertInstanceOf('AndyTruong\Common\Context', $context);
        $this->assertInstanceOf('ArrayAccess', $context);
    }

    /**
     * @dataProvider dataProviderGetSet
     */
    public function testGetSet($key, $value)
    {
        $context = $this->getContext();

        // No value configured yet
        // Test offsetExists() & offsetGet() methods
        $this->assertFalse(isset($context[$key]));
        $this->assertNull($context[$key]);

        // Test offsetSet()
        $context[$key] = $value;
        $this->assertEquals($value, $context->offsetGet($key));

        // Test offsetUnset
        unset($context[$key]);
        $this->assertFalse(isset($context[$key]));
        $this->assertNull($context[$key]);
    }

    /**
     * @dataProvider dataProviderGetSet
     */
    public function testNullKey($key, $value)
    {
        $context = $this->getContext();
        $context[] = $value;

        $internal = $context->getContainer();
        $this->assertEquals($value, array_pop($internal));
    }

    public function dataProviderGetSet()
    {
        return array(
            array('number', 1),
            array('string', 'Hello PHP'),
            array('array', array('Array')),
            array('object', new DateTime()),
            array('closure', function() {
                return 'closure';
            })
        );
    }

}
