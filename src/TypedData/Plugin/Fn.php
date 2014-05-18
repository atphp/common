<?php

namespace AndyTruong\Common\TypedData\Plugin;

class Fn extends String
{

    public function isEmpty()
    {
        return FALSE;
    }

    public function validateInput(&$error = NULL)
    {
        if (!is_string($this->value)) {
            $error = 'Function name must be a string.';
            return FALSE;
        }

        if (!function_exists($this->value)) {
            $error = 'Function does not exist.';
            return FALSE;
        }

        return parent::validateInput($error);
    }

}
