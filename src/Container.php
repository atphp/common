<?php

namespace AndyTruong\Common;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Service Container
 *
 * @event at.container.not_found Is fired when a service is not found.
 */
class Container extends \Pimple implements EventManagerAwareInterface {
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
  protected $em_name = 'at.container';

  /**
   * Set event-manager object.
   *
   * @param \Zend\EventManager\EventManagerInterface $em
   * @return \AndyTruong\Common\Container
   */
  public function setEventManager(EventManagerInterface $em) {
    $em->setIdentifiers(array(__CLASS__, get_called_class()));
    $this->em = $em;
    return $this;
  }

  /**
   * Get event-manager object.
   *
   * @return EventManagerInterface
   */
  public function getEventManager() {
    if (null === $this->em) {
      $this->setEventManager($em = at_event_manager($this->em_name));
    }
    return $this->em;
  }

  /**
   * Wrap default offsetGet method to provide lazy loading…
   *
   * @param type $id
   * @return type
   */
  public function offsetGet($id) {
    if (!$this->offsetExists($id)) {
      $this->getEventManager()->trigger('at.container.not_found', $this, array($id));
    }

    return parent::offsetGet($id);
  }
}
