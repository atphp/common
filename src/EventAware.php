<?php

namespace AndyTruong\Common;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Extends this to make extensible class.
 */
class EventAware implements EventManagerAwareInterface {
  protected $em;

  public function setEventManager(EventManagerInterface $em) {
    $em->setIdentifiers(array(__CLASS__, get_called_class()));
    $this->em = $em;
    return $this;
  }

  public function getEventManager() {
    if (null === $this->em) {
      $this->setEventManager(new EventManager());
    }
    return $this->em;
  }
}
