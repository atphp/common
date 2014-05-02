<?php
namespace AndyTruong\Common;

use \Twig_Extension;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * TwigExtension — gate for users to add custom features to TwigEnviroment
 *
 * @see TwigFactory::getTwigEnvironment()
 * @event at.twig.extension.filters
 * @event at.twig.extension.functions
 * @event at.twig.extension.globals
 */
class TwigExtension extends Twig_Extension implements EventManagerAwareInterface {
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
  protected $em_name = 'at.twig.extension';

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

  public function getName() {
    return 'AT Common';
  }

  /**
   *
   * @return array
   * @event at.twig.extension.filters
   */
  function getFilters() {
    $items = array();

    $this->getEventManager()->trigger('at.twig.extension.filters', $this, $items);

    return $items;
  }

  function getFunctions() {
    $items = array();

    $this->getEventManager()->trigger('at.twig.extension.functions', $this, $items);

    return $items;
  }

  function getGlobals() {
    $items = array();

    $this->getEventManager()->trigger('at.twig.extension.globals', $this, $items);

    return $items;
  }
}
