<?php

namespace AndyTruong\Common\TestCases\Services;

use DateTime;

class ControllerResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testObjectMethodPair()
    {
        $obj = new DateTime();
        $input = array($obj, 'foo');
        $expected = array($obj, 'foo');
        $actual = at_controller_resolver()->get($input);
        $this->assertEquals($expected, $actual);
    }

    public function testObjectInvoke()
    {
        if (version_compare(PHP_VERSION, '5.4', '<')) {
            $this->markTestSkipped('PHP before 5.4 does not support __invoke() magic method.');
            return;
        }

        $class = 'AndyTruong\Common\TestCases\Services\InvokableClass';
        $input = new $class();
        $this->assertInstanceOf($class, at_controller_resolver()->get($input));
    }

    public function testClassStringInvoke()
    {
        if (version_compare(PHP_VERSION, '5.4', '<')) {
            $this->markTestSkipped('PHP before 5.4 does not support __invoke() magic method.');
            return;
        }

        $input = $expected = 'AndyTruong\Common\TestCases\Services\InvokableClass';
        $this->assertInstanceOf($expected, at_controller_resolver()->get($input));
    }

}

class InvokableClass
{

    protected $foo = 'bar';

    public function __invoke()
    {
        return $this->foo;
    }

}
