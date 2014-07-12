<?php

namespace AndyTruong\Common;

use ArrayAccess;
use Symfony\Component\EventDispatcher\Event as BaseEvent;
use Zend\EventManager\Exception\InvalidArgumentException;

/**
 * More flexible than Symfony's default Event object. Developer needs not to
 * create new Event everytime they need it.
 */
class Event extends BaseEvent
{

    /**
     * @var string|object The event target
     */
    protected $target;

    /**
     * @var array|ArrayAccess|object The event parameters.
     */
    protected $params;

    /**
     * Constructor
     *
     * Accept a target and its parameters.
     *
     * @param string $name Event name
     * @param string|object $target
     * @param array|ArrayAccess $params
     */
    public function __construct($name = null, $target = null, $params = null)
    {
        if (null !== $name) {
            $this->setName($name);
        }

        if (null !== $target) {
            $this->setTarget($target);
        }

        if (null !== $params) {
            $this->setParams($params);
        }
    }

    /**
     * Set target.
     *
     * @param string|Object $target
     * @return Event
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * Set params.
     *
     * @param array|ArrayAccess|object $params
     * @return Event
     * @throws Exception\InvalidArgumentException
     */
    public function setParams($params)
    {
        if (!is_array($params) && !is_object($params)) {
            throw new InvalidArgumentException(sprintf(
                'Event parameters must be an array or object; received "%s"', gettype($params)
            ));
        }

        $this->params = $params;
        return $this;
    }

}
