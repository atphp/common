<?php

namespace AndyTruong\Common\TypedData\Plugin;

abstract class Base implements PluginInterface
{

    protected $definition;
    protected $input;

    public function __construct($definition = NULL, $input = NULL)
    {
        !is_null($definition) && $this->setDefinition($definition);
        !is_null($input) && $this->setInput($input);
    }

    public function setDefinition($definition)
    {
        $this->definition = $definition;
        return $this;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function setInput($input)
    {
        $this->input = $input;
        return $this;
    }

    public function getInput() {
        return $this->input;
    }

    public function isEmpty()
    {
        return false;
    }

    /**
     * Alias of self::validate()
     * @return boolean
     */
    public function isValid(&$error = NULL)
    {
        return $this->validate($error);
    }

    public function getValue()
    {
        if ($this->validate()) {
            return $this->input;
        }
    }

    public function validate(&$error = NULL)
    {
        return $this->validateDefinition($error) && $this->validateInput($error);
    }

    protected function validateDefinition(&$error)
    {
        if (!is_array($this->definition)) {
            $error = 'Data definition must be an array.';
            return FALSE;
        }
        return TRUE;
    }

    protected function validateInput(&$error)
    {
        if (!empty($this->definition['validate'])) {
            return $this->validateUserCallacks($error);
        }
        return TRUE;
    }

    protected function validateUserCallacks(&$error)
    {
        foreach ($this->definition['validate'] as $callback) {
            if (is_callable($callback)) {
                if (!$callback($this->input, $error)) {
                    return FALSE;
                }
            }
        }

        return TRUE;
    }

}
