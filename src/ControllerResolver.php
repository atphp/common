<?php

namespace AndyTruong\Common;

use AndyTruong\Event\EventAware;

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
 *  $callable = (new ControllerResolver())->get($input);
 *
 * @consider Provide `at.controller_resolver.info` event, let developer can provide more matchers.
 */
class ControllerResolver extends EventAware
{

    /**
     * Name of event-manager.
     *
     * @var string
     */
    protected $em_name = 'at.controller_resolver';

    /**
     * Container for matching processers.
     *
     * @var array
     */
    protected $matchers = array();

    /**
     * Add a custom matching processer to controller resolver.
     *
     * @param callable $matcher
     * @param string $input_type
     */
    public function addMatcher($matcher, $input_type = 'all')
    {
        $this->matchers[$input_type][] = $matcher;
    }

    /**
     * Get matchers.
     *
     * @param string $input_type
     * @return array()
     */
    public function getMatchers($input_type = NULL)
    {
        if (empty($this->matchers)) {
            $this->addMatcher(array($this, 'detectFunction'), 'string');
            $this->addMatcher(array($this, 'detectStatic'), 'string');
            $this->addMatcher(array($this, 'detectPair'), 'array');
            $this->addMatcher(array($this, 'detectMagic'), 'object');

            // Developers can hook to this event to add more matchers.
            $this->trigger('at.controller_resolver.info', $this);
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
    public function get($input)
    {
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
    protected function detectPair($input)
    {
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
    protected function detectMagic($input)
    {
        if (method_exists($input, '__invoke')) {
            return $input;
        }
    }

    /**
     * Input is class::method
     */
    protected function detectStatic($input)
    {
        if (strpos($input, '::') !== FALSE) {
            list($class, $method) = explode('::', $input, 2);
            return array($class, $method);
        }
    }

    /**
     * Input is a name of function.
     */
    protected function detectFunction($input)
    {
        if (method_exists($input, '__invoke')) {
            return new $input;
        }

        if (function_exists($input)) {
            return $input;
        }
    }

}
