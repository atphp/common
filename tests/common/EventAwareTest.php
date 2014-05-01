<?php

namespace AndyTruong\Common\TestCases;

class Foo extends \AndyTruong\Common\EventAware {
  public function bar($baz, $bat = null) {
    $params = compact('baz', 'bat');
    $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
  }
}

class EventAwareTest extends \PHPUnit_Framework_TestCase {
  public function test() {
    $log = '%s called on %s, using params %s';
    $foo = new Foo();
    $foo->getEventManager()->attach('bar', function ($e) use (&$log) {
        $event  = $e->getName();
        $target = get_class($e->getTarget());
        $params = json_encode($e->getParams());

        $log = sprintf($log, $event, $target, $params);
    });

    $foo->bar('baz', 'bat');

    $this->assertEquals($log, 'bar called on AndyTruong\Common\TestCases\Foo, using params {"baz":"baz","bat":"bat"}');
  }
}
