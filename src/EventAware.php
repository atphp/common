<?php

namespace AndyTruong\Common;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Extends this to make extensible class.
 */
abstract class EventAware implements EventManagerAwareInterface {
  protected $events;

  public function setEventManager(EventManagerInterface $events) {
    $events->setIdentifiers(array(__CLASS__, get_called_class()));
    $this->events = $events;
    return $this;
  }

  public function getEventManager() {
    if (null === $this->events) {
      $this->setEventManager(new EventManager());
    }
    return $this->events;
  }
}
