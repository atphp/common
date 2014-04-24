<?php
/**
 * @file
 * Common functions
 */

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
