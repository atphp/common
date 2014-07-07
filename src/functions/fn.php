<?php

/**
 * @file fn.php
 * Common functions
 */

use AndyTruong\Common\ControllerResolver,
    AndyTruong\Common\Context,
    AndyTruong\Common\TypedData\Manager as TypedDataManager,
    Zend\EventManager\EventManager;

/**
 * Can not: new Thing()->doStuff();
 * But: at_id(new Thing())->doStuff();
 */
if (!function_exists('at_id')) {

    function at_id($x)
    {
        return $x;
    }

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
function at_newv($class_name, $argv = array())
{
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
 * @param string $name
 * @param EventManager $event_manager
 * @return EventManager
 * @see atc()
 */
function at_event_manager($name = 'default', $event_manager = NULL)
{
    static $managers = array();

    // Let use alter default event manager
    if (function_exists('at_event_manager_before') && is_null($event_manager)) {
        at_event_manager_before($name, $event_manager);
    }

    if (!isset($managers[$name]) && !is_null($event_manager)) {
        $managers[$name] = $event_manager;
    }

    if (!empty($managers[$name])) {
        return $managers[$name];
    }

    if (!isset($managers['default'])) {
        $managers['default'] = new EventManager();
    }

    return $managers['default'];
}

/**
 * Wrapper for typped-data, an API to make sure data provided is matched to its schema.
 *
 * Examples: Check test cases under ./tests/common/Services/TypedData/ for
 *  example usage.
 *
 * We can provide extra typed-data via event manager:
 *
 *  $em = new EventManager();
 *  $em->attach('at.typed_data.plugin.load', function ($event) {
 *    // @var $manager TypedDataManager
 *    // CustomTypeClass must implements AndyTruong\Common\TypedData\Plugin\PluginInterface
 *    $manager->registerPlugin('type.custom_type', 'CustomTypeClass');
 *  });
 *  at_event_manager('at.typed_data', $em);
 *
 * @param array $definition
 * @param mixed $input
 * @return mixed
 * @throws \Exception
 */
function at_data($definition, $input = NULL)
{
    static $manager;

    if (is_null($manager)) {
        $manager = new TypedDataManager();
    }

    return $manager->getPlugin($definition, $input);
}
