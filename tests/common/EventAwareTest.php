<?php

namespace AndyTruong\Common\TestCases;

use AndyTruong\Common\Event;
use AndyTruong\Common\Fixtures\EventAwareClass;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * @group event
 */
class EventAwareTest extends PHPUnit_Framework_TestCase
{

    private function getEventAwareObject()
    {
        return new EventAwareClass();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Listener is trigged
     */
    public function testDispatch()
    {
        $obj = $this->getEventAwareObject();
        $obj->getDispatcher()->addListener('nothing', function ($e) use ($obj) {
            throw new RuntimeException('Listener is trigged');
        });
        $obj->nothing();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Listener is trigged
     */
    public function testTriggerNoParam()
    {
        $obj = $this->getEventAwareObject();
        $obj->getDispatcher()->addListener('noParams', function ($e) {
            throw new RuntimeException('Listener is trigged');
        });
        $obj->noParams();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Listener is trigged: baz, bat
     */
    public function testTriggerFullOptions()
    {
        $obj = $this->getEventAwareObject();
        $obj->getDispatcher()->addListener('full', function (Event $e) {
            throw new RuntimeException('Listener is trigged: ' . implode(', ', $e->getParams()));
        });
        $obj->full('baz', 'bat');
    }

    public function testTriggerUntil()
    {
        $stopped_at = null;

        $obj = $this->getEventAwareObject();

        $obj->getDispatcher()->addListener('custom_event', function($event) use (&$stopped_at) {
            $stopped_at = 'ONE';
            $event->stopPropagation();
        });

        $obj->getDispatcher()->addListener('custom_event', function($event) use (&$stopped_at) {
            $stopped_at = 'TWO';
        });

        $obj->dispatch('custom_event');

        $this->assertEquals('ONE', $stopped_at);
    }

}
