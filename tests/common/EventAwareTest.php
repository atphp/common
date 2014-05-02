<?php

namespace AndyTruong\Common\TestCases;

class Foo extends \AndyTruong\Common\EventAware {
  public $counter = 0;

  public function increase() {
    $this->counter++;
  }

  public function nothing() {
    $this->getEventManager()->trigger(__FUNCTION__);
  }

  public function noParams() {
    $this->getEventManager()->trigger(__FUNCTION__, $this);
  }

  public function full($baz, $bat = null) {
    $params = compact('baz', 'bat');
    $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
  }

  public function triggerUntil() {
    $this->getEventManager()->triggerUntil(__FUNCTION__, $this, array(), function($v) {
      return 'STOP' === $v;
    });
  }

  public function stopPropagation() {
    $this->getEventManager()->trigger(__FUNCTION__, $this);
  }

  /**
   * @return ResponseCollection
   */
  public function collectValues() {
    return $this->getEventManager()->trigger(__FUNCTION__, $this);
  }
}

class EventAwareTest extends \PHPUnit_Framework_TestCase {
  public function testTrigger() {
    $foo = new Foo();

    // @event nothing
    $foo->getEventManager()->attach('nothing', function ($e) use ($foo) { $foo->increase(); });
    $foo->nothing();
    $this->assertEquals(1, $foo->counter);

    // @event noParams, fire event without params.
    $foo->getEventManager()->attach('noParams', function ($e) { $e->getTarget()->increase(); });
    $foo->noParams();
    $this->assertEquals(2, $foo->counter);

    // @event basic
    $foo->getEventManager()->attach('full', function ($e) { $e->getTarget()->increase(); });
    $foo->full('baz', 'bat');
    $this->assertEquals(3, $foo->counter);

    // @event triggerUntil
    $foo->getEventManager()->attach('triggerUntil', function ($e) { $e->getTarget()->increase(); });
    $foo->getEventManager()->attach('triggerUntil', function ($e) { $e->getTarget()->increase(); return 'STOP'; });
    $foo->getEventManager()->attach('triggerUntil', function ($e) { $e->getTarget()->increase(); });
    $foo->triggerUntil();
    $this->assertEquals(5, $foo->counter);
  }

  public function testCollection() {
    $foo = new Foo();

    // @event stopPropagation, a listener can break the listeners loop.
    $foo->getEventManager()->attach('stopPropagation', function ($e) { $e->getTarget()->increase(); $e->stopPropagation(); });
    $foo->getEventManager()->attach('stopPropagation', function ($e) { $e->getTarget()->increase(); });
    $foo->stopPropagation();
    $this->assertEquals(1, $foo->counter, 'First listener breaks others');

    // @event collectValues
    $foo->getEventManager()->attach('collectValues', function ($e) { return 'A'; });
    $foo->getEventManager()->attach('collectValues', function ($e) { return 'B'; });
    $foo->getEventManager()->attach('collectValues', function ($e) { return 'C'; });
    $values = $foo->collectValues();
    $this->assertEquals('Zend\EventManager\ResponseCollection', get_class($values));
    $this->assertEquals(3, $values->count());
  }
}
