<?php

namespace AndyTruong\Common;

use ArrayAccess;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This Trait is only available when we use this library with symfony/event-dispatcher:~2.5.0.
 *
 * Example:
 *
 *  class MyClass extends \AndTruong\Common\EventAware {
 *      public function myEventAwareMethod() {
 *          $this->dispatch('my.event.before');
 *          $event = new \Symfony\Component\EventDispatcher\Event();
 *          $this->dispatch('my.event.after', $event);
 *
 *          // or simpler
 *          $this->trigger('my.other.event', $this, ['param 1', 'param 2']);
 *      }
 *  }
 *
 * // Class usage
 * $myobj = new MyClass();
 * $myobj->getDispatcher()->addListener('my.event.before', function(\AndyTruong\Common\Event $e) {
 *      $e->getTarget(); // instance of MyClass
 *      $e->getParams(); // ['param 1', 'param 2']
 * });
 * $myobj->myEventAwareMethod();
 */
class EventAware
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

    /**
     * Dispatch an event with extra params.
     *
     * @param string $event_name
     * @param string|object $target
     * @param array|ArrayAccess $params
     */
    public function trigger($event_name, $target, $params = array())
    {
        $event = new Event($event_name, $target, $params);
        return $this->getDispatcher()->dispatch($event_name, $event);
    }

}
