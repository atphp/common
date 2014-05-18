<?php

namespace AndyTruong\Common\TypedData\Plugin;

class Integer extends Base
{

    public function isEmpty()
    {
        if (!is_null($this->input)) {
            return $this->input === 0;
        }
    }

    public function validateInput(&$error = NULL)
    {
        if (!is_int($this->input)) {
            $error = 'Input is not an integer value.';
            return FALSE;
        }
        return parent::validateInput($error);
    }

}
