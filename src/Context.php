<?php

namespace AndyTruong\Common;

use ArrayAccess;

/**
 * Super simple container, just informations which only available in request scope.
 */
class Context implements ArrayAccess
{

    protected $container = array();

    /**
     * Get container.
     *
     * @return array
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * {inheritdoc}
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        }
        else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * {inheritdoc}
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * {inheritdoc}
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * {inheritdoc}
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

}
