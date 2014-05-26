<?php

namespace AndyTruong\Common\TestCases;

use Zend\EventManager\SharedEventManager;

class Foo extends \AndyTruong\Common\EventAware
{

    public $counter = 0;
    public $logs = array();

    public function increase($msg = '')
    {
        $this->counter++;
        if (!empty($msg)) {
            $this->logs[] = $msg;
        }
    }

    public function nothing()
    {
        $this->getEventManager()->trigger(__FUNCTION__);
    }

    public function noParams()
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this);
    }

    public function full($baz, $bat = null)
    {
        $params = compact('baz', 'bat');
        $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
    }

    public function triggerUntil()
    {
        $this->getEventManager()->triggerUntil(__FUNCTION__, $this, array(), function($v) {
            return 'STOP' === $v;
        });
    }

    public function stopPropagation()
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this);
    }

    /**
     * @return ResponseCollection
     */
    public function collectValues()
    {
        return $this->getEventManager()->trigger(__FUNCTION__, $this);
    }

}

class EventAwareTest extends \PHPUnit_Framework_TestCase
{

    public function getFoo()
    {
        $foo = new Foo();
        $this->assertEquals(0, $foo->counter);

        // Clear all previous listeners
        $foo->getEventManager()->unsetSharedManager();
        foreach ($foo->getEventManager()->getEvents() as $event) {
            $foo->getEventManager()->clearListeners($event);
        }

        return $foo;
    }

    public function testTrigger()
    {
        $foo = new Foo();

        // @event nothing
        $foo->getEventManager()->attach('nothing', function ($e) use ($foo) {
            $foo->increase('trigger:nothing');
        });
        $foo->nothing();
        $this->assertEquals(1, $foo->counter);

        // @event noParams, fire event without params.
        $foo->getEventManager()->attach('noParams', function ($e) {
            $e->getTarget()->increase('trigger:noParams');
        });
        $foo->noParams();
        $this->assertEquals(2, $foo->counter);

        // @event basic
        $foo->getEventManager()->attach('full', function ($e) {
            $e->getTarget()->increase('trigger:full');
        });
        $foo->full('baz', 'bat');
        $this->assertEquals(3, $foo->counter);

        // @event triggerUntil
        $foo->getEventManager()->attach('triggerUntil', function ($e) {
            $e->getTarget()->increase('trigger:triggerUntil:1');
        });
        $foo->getEventManager()->attach('triggerUntil', function ($e) {
            $e->getTarget()->increase('trigger:triggerUntil:2');
            return 'STOP';
        });
        $foo->getEventManager()->attach('triggerUntil', function ($e) {
            $e->getTarget()->increase('trigger:triggerUntil:3');
        });
        $foo->triggerUntil();
        $this->assertEquals(5, $foo->counter);
    }

    public function testCollection()
    {
        $foo = $this->getFoo();

        // @event stopPropagation, a listener can break the listeners loop.
        $foo->getEventManager()->attach('stopPropagation', function ($e) {
            $e->getTarget()->increase('collection:stop:1');
            $e->stopPropagation();
        });
        $foo->getEventManager()->attach('stopPropagation', function ($e) {
            $e->getTarget()->increase('collection:stop:2');
        });
        $foo->stopPropagation();
        $this->assertEquals(1, $foo->counter, 'First listener breaks others');

        // @event collectValues
        $foo->getEventManager()->attach('collectValues', function ($e) {
            return 'A';
        });
        $foo->getEventManager()->attach('collectValues', function ($e) {
            return 'B';
        });
        $foo->getEventManager()->attach('collectValues', function ($e) {
            return 'C';
        });
        $values = $foo->collectValues();
        $this->assertEquals('Zend\EventManager\ResponseCollection', get_class($values));
        $this->assertEquals(3, $values->count());
    }

    /**
     * Wildcard listener feature.
     *
     * @dataProvider dataProviderWildcartListener
     */
    public function testWildcartListener($events = array(), $event_alias = NULL)
    {
        $foo = $this->getFoo();

        // Attach wildcard listener
        $foo->getEventManager()->attach(is_null($event_alias) ? array_keys($events) : '*', function($e) {
            $e->getTarget()->increase('wildcardListener:' . __LINE__);
        });

        // Action!
        foreach ($events as $method => $args) {
            call_user_func_array(array($this, $method), $args);
        }

        // Assert
        $this->assertEquals(count($events), $foo->counter);
    }

    public function dataProviderWildcartListener()
    {
        return array(
            array(array('full' => array('baz', 'bat'), 'noParams' => array())),
            array(array('full' => array('baz', 'bat'), 'noParams' => array(), 'nothing' => array()), '*'),
        );
    }

    /**
     * SharedEventManager feature.
     */
    public function testSharedEventManager()
    {
        $event = new SharedEventManager();
        $event->attach('AndyTruong\Common\TestCases\Foo', 'noParams', function($e) {
            $e->getTarget()->increase('sharedEvent:noParams');
        });

        $foo = $this->getFoo();
        $foo->getEventManager()->setSharedManager($event);
        $foo->noParams();
        $this->assertEquals(1, $foo->counter);
    }

}
