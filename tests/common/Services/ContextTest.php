<?php
namespace AndyTruong\Common\TestCases\Services;

use AndyTruong\Common\Context;

class ContextTest extends \PHPUnit_Framework_TestCase {
    public function testInfo() {
        $this->assertInstanceOf('AndyTruong\Common\Context', at_context());
    }

    /**
     * @dataProvider dataProviderGetSet
     */
    public function testGetSet($key, $value) {
        $this->assertNull(at_context($key));

        at_context($key, $value);

        $this->assertEquals($value, at_context($key));
    }

    public function dataProviderGetSet() {
        return array(
            array('number',  1),
            array('string',  'Hello PHP'),
            array('array',   array('Array')),
            array('object',  new \DateTime()),
            array('closure', function() { return 'closure'; })
        );
    }
}