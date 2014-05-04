<?php

namespace AndyTruong\Common;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Service Container
 *
 * @event at.container.not_found Is fired when a service is not found.
 * @event at.container.find_by_tag Is fired when container find services by tag.
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
   * @param string $id
   * @return mixed
   */
  public function offsetGet($id, $args = NULL) {
    if (!is_null($args)) {
      $this["{$id}:arguments"] = $args;
    }

    if (!$this->offsetExists($id)) {
      $this->getEventManager()->trigger('at.container.not_found', $this, array($id, $args));
    }

    return parent::offsetGet($id);
  }

  /**
   * Find services by tag
   *
   * @param  string  $tag
   * @param  string  $return Type of returned services,
   * @return array
   */
  public function find($tag, $return = 'service_name') {
    $defs = array();

    foreach ($this->getEventManager()->trigger('at.container.find_by_tag', $this, array($tag, $return)) as $_defs) {
      $defs = array_merge($defs, $_defs);
    }

    return $defs;
  }
}
