<?php

namespace AndyTruong\Common\Traits;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This Trait is only available when we use this library with symfony/event-dispatcher:~2.5.0.
 *
 * Example:
 *
 *  class MyClass {
 *      use \AndyTruong\Common\Traits\EventAwareTrait;
 *
 *      public function myEventAwareMethod() {
 *          $this->dispatch('my.event.before');
 *          $event = new \Symfony\Component\EventDispatcher\Event();
 *          $this->dispatch('my.event.after', $event);
 *      }
 *  }
 */
trait EventAwareTrait
{

    protected $dispatcher;

    /**
     * Inject dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get dispatcher.
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        if (null === $this->dispatcher) {
            $this->setDispatcher($this->getDefaultDispatcher());
        }

        return $this->dispatcher;
    }

    /**
     * Generate default dispatcher, override this if your class would like to
     * provide an other default dispatcher.
     *
     * @return EventDispatcher
     */
    protected function getDefaultDispatcher()
    {
        return new EventDispatcher();
    }

    /**
     * Shortcut to dispatch an event.
     *
     * @param string $event_name
     * @param Event $event
     * @return Event
     */
    public function dispatch($event_name, Event $event = null)
    {
        return $this->getDispatcher()->dispatch($event_name, $event);
    }

}
