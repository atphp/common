<?php

namespace AndyTruong\Common\TypedData\Plugin;

class Boolean extends Base
{

    public function isEmpty()
    {
        if (!is_null($this->input)) {
            return $this->input === FALSE;
        }
    }

    public function validateInput(&$error = NULL)
    {
        if (!is_bool($this->input)) {
            $error = 'Input is not a boolean value.';
            return FALSE;
        }
        return parent::validateInput($error);
    }

}
