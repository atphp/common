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
        $dispatcher = $obj->getDispatcher();
        $dispatcher->addListener('my_event', function(Event $event) {
            throw new \RuntimeException('Event is executed.');
        });
        $obj->dispatch('my_event');
    }

}
