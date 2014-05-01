<?php
/**
 * @file
 * Common functions
 */

use AndyTruong\Common\Container;
use Zend\EventManager\EventManager;

/**
 * Can not:
 *    new Thing()->doStuff();
 *
 * Can:
 *    at_id(new Thing())->doStuff();
 */
function at_id($x) {
  return $x;
}

/**
 * Invokes the "new" operator with a vector of arguments. There is no way to
 * call_user_func_array() on a class constructor, so you can instead use this
 * function:
 *
 * $obj = at_newv($class_name, $argv);
 *
 * That is, these two statements are equivalent:
 *
 * $pancake = new Pancake('Blueberry', 'Maple Syrup', true);
 * $pancake = newv('Pancake', array('Blueberry', 'Maple Syrup', true));
 *
 * @param  string  The name of a class.
 * @param  list    Array of arguments to pass to its constructor.
 * @return obj     A new object of the specified class, constructed by passing
 *                  the argument vector to its constructor.
 */
function at_newv($class_name, $argv = array()) {
  $reflector = new ReflectionClass($class_name);
  if ($argv) {
    return $reflector->newInstanceArgs($argv);
  }
  return $reflector->newInstance();
}

/**
 * Get event Manager.
 *
 * @staticvar array $managers
 * @param string $id
 * @param EventManager $event_manager
 * @return EventManager
 */
function at_event_manager($id = 'default', $event_manager = NULL) {
  static $managers = array();

  if (!isset($managers[$id]) && !is_null($event_manager)) {
    $managers[$id] = $event_manager;
  }

  if (!empty($managers[$id])) {
    return $managers[$id];
  }

  if (!isset($managers['default'])) {
    $managers['default'] = new EventManager();
  }

  return $managers['default'];
}

/**
 * Shortcut to get/set service from/to container.
 *
 * Example: Basic usages
 *
 *    atc('isset', 'foo');      // <-- false
 *    atc('set', 'foo', 'bar'); // <-- set 'bar' string to 'foo' service
 *    echo atc('get', 'foo');   // <-- 'bar'
 *    atc('unset', 'foo');      // <-- Unset 'foo' service
 *
 * Example: Lazy service fetcher
 *
 *    // Create event-manager and save it in event-manager container
 *    $em = new Zend\EventManager\EventManager();
 *    $em->attach('at.container.not_found', function ($e) {
 *      $c = $e->getTarget();
 *      list($id) = $e->getParams();
 *      atc('set', $id, "Getting {$id}");
 *    });
 *    at_event_manager('at.container', $em);
 *
 *    echo atc('get', 'FOO'); // <-- 'Getting FOO'
 *
 * @staticvar Container $container
 * @param string $method
 * @param string $id
 * @param mixed $value
 * @return Container
 */
function atc($method = NULL, $id = NULL, $value = NULL) {
  static $container;

  if (is_null($container)) {
    $container = new Container();
    $em = at_event_manager('at.container');
    $container->setEventManager($em);
  }

  if (is_null($id) && is_null($method)) {
    return $container;
  }

  switch ($method) {
    case 'isset':
    case 'offsetExists':
    case 'exists':
      return $container->offsetExists($id);

    case 'offsetSet':
    case 'set':
    case 'share':
      return $container->offsetSet($id, $value);

    case 'protect':
      return $container->protect(function() use ($value) { return $value; });

    case 'factory':
      return $container->factory($value);

    case 'raw':
      return $container->raw($id);

    case 'offsetUnset':
    case 'delete':
    case 'remove':
    case 'destroy':
    case 'unset':
      return $container->offsetUnset($id);

    case 'get':
    case 'offsetGet':
    default:
      return $container->offsetGet($id);
  }
}
