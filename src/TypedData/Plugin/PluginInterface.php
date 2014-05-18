<?php

namespace AndyTruong\Common\TypedData\Plugin;

interface PluginInterface
{
    /**
     * Set definition for typed-data.
     */
    public function setDefinition($definition);

    /**
     * Set input for typed-data.
     */
    public function setInput($value);

    /**
     * return boolean
     */
    public function isEmpty();

    /**
     * Validate typed-data value.
     */
    public function validate(&$error);
}
