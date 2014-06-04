<?php

namespace AndyTruong\Common\TestCases\Services;

use DateTime;

class ControllerResolverTest extends \PHPUnit_Framework_TestCase
{

    private function getControllerResolver()
    {
        return at_controller_resolver();
    }

    public function testFunction()
    {
        $this->assertEquals('time', $this->getControllerResolver()->get('time'));
    }

    public function testStaticMethod()
    {
        $this->assertEquals(
            array('StaticMethodClass', 'staticMethod'),
            $this->getControllerResolver()->get('StaticMethodClass::staticMethod')
        );
    }

    public function testObjectMethodPair()
    {
        $obj = new DateTime();
        $input = array($obj, 'foo');
        $expected = array($obj, 'foo');
        $this->assertEquals($expected, $this->getControllerResolver()->get($input));
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

class StaticMethodClass
{

    public static function staticMethod($x)
    {
        return $x;
    }

}
