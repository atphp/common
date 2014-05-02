<?php
namespace AndyTruong\Common;

/**
 *
 * Get controller from definition.
 *
 * Input can be:
 *  - name of function.
 *  - name of class
 *  - pair of object, method
 *  - Twig string
 *
 * Example:
 *
 *  use AndyTruong\Common\ControllerResolver;
 *  $callable = at_id(new ControllerResolver())->get($input);
 *
 * @event at.controller_resolver.info Is fired when clas collect matcher info.
 */
class ControllerResolver extends EventAware {
  protected $matchers = array();

  public function addMatcher($matcher, $input_type = 'all') {
    $this->matchers[$input_type][] = $matcher;
  }

  public function getMatchers($input_type = NULL) {
    if (empty($this->matchers)) {
      $this->addMatcher(array($this, 'detectFunction'), 'string');
      $this->addMatcher(array($this, 'detectService'), 'string');
      $this->addMatcher(array($this, 'detectStatic'), 'string');
      $this->addMatcher(array($this, 'detectTwig'), 'string');
      $this->addMatcher(array($this, 'detectPair'), 'array');
      $this->addMatcher(array($this, 'detectMagic'), 'object');

      // Developers can hook to this event to add more matchers.
      $this->getEventManager()->trigger('at.controller_resolver.info', $this);
    }

    $matchers = isset($this->matchers[$input_type]) ? $this->matchers[$input_type] : array();

    if (isset($this->matchers['all'])) {
      $matchers = array_merge($matchers, $this->matchers['all']);
    }

    return $matchers;
  }

  /**
   * Get callable variable from mixed input.
   *
   * @param mixed $input
   * @return callable
   */
  public function get($input) {
    foreach ($this->getMatchers(gettype($input)) as $callable) {
      if ($controller = call_user_func_array($callable, array($input))) {
        return $controller;
      }
    }
  }

  /**
   * definition: [Foo, bar]
   *
   * @return array
   */
  protected function detectPair($input) {
    if (is_array($input) && 2 === count($input)) {
      return $input;
    }
  }

  /**
   * Input is an object which has __invoke method, a magic method.
   *
   * @param object $input
   * @return null|callable
   */
  protected function detectMagic($input) {
    if (method_exists($input, '__invoke')) {
      return $input;
    }
  }

  /**
   * Input is class::method
   */
  protected function detectStatic($input) {
    if (strpos($input, '::') !== FALSE) {
      list($class, $method) = explode('::', $input, 2);
      return array($class, $method);
    }
  }

  /**
   * Input is a name of function.
   */
  protected function detectFunction($input) {
    if (method_exists($input, '__invoke')) {
      return new $input;
    }

    if (function_exists($input)) {
      return $input;
    }
  }

  protected function detectTwig($input) {
    $is_twig_1 = FALSE !== strpos($input, '{{');
    $is_twig_2 = FALSE !== strpos($input, '{%');
    if ($is_twig_1 || $is_twig_2) {
      $twig = at_twig();
      return array($twig, 'render');
    }
  }

  /**
   * Definition is service_name:service_method
   *
   * @return null|callable
   */
  protected function detectService($input) {
    if (FALSE !== strpos($input, ':')) {
      list($service, $method) = explode(':', $input, 2);
      return array(at_container($service), $method);
    }
  }
}
