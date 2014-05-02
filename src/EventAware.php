<?php

namespace AndyTruong\Common;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Extends this to make extensible class.
 */
class EventAware implements EventManagerAwareInterface {
  /**
   * Event-manager object.
   *
   * @var EventManagerInterface
   */
  protected $em;

  /**
   * Name of event-manager.
   *
   * @var string
   */
  protected $em_name;

  public function setEventManager(EventManagerInterface $em) {
    $em->setIdentifiers(array(__CLASS__, get_called_class()));
    $this->em = $em;
    return $this;
  }

  public function getEventManager() {
    if (null === $this->em) {
      $em_name = !is_null($this->em_name) ? $this->em_name : str_replace('\\', '.', get_called_class());
      $this->setEventManager($em = at_event_manager($em_name));
    }
    return $this->em;
  }
}
