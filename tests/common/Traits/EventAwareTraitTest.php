<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\EventAwareClass;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @group event
 */
class EventAwareTraitTest extends TraitTestCase
{

    public function testSetter()
    {
        $obj = new EventAwareClass();
        $dispatcher = new EventDispatcher();
        $obj->setDispatcher($dispatcher);
        $this->assertSame($dispatcher, $obj->getDispatcher());
    }

    public function testGetDefaultDispatcher()
    {
        $obj = new EventAwareClass();
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcherInterface', $obj->getDispatcher());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Event is executed.
     */
    public function testDispatchShortcut()
    {
        $obj = new EventAwareClass();
        $obj->getDispatcher()->addListener('my_event', function(Event $event) {
            throw new RuntimeException2('Event is executed.');
        });
        $obj->dispatch('my_event');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Event is executed. Class: AndyTruong\Common\Fixtures\Traits\EventAwareClass. Params: param_1, param_2
     */
    public function testDispatchCustomEvent()
    {
        $obj = new EventAwareClass();
        $obj->getDispatcher()->addListener('my_event', function(CustomEvent $event) {
            $event->getTarget();
            $msg = sprintf(
                'Event is executed. Class: %s. Params: %s'
                , get_class($event->getTarget())
                , implode(', ', $event->getParams())
            );
            throw new RuntimeException2($msg);
        });
        $event = new CustomEvent('event_aware_class', $obj, array('param_1', 'param_2'));
        $obj->dispatch('my_event', $event);
    }

}
