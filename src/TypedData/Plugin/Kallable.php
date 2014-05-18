<?php

namespace AndyTruong\Common\TypedData\Plugin;

class Kallable extends String
{

    public function isEmpty()
    {
        return is_null($this->input) || empty($this->input);
    }

    protected function validateInput(&$error = NULL)
    {
        if (!is_callable($this->input)) {
            $error = 'Function does not exist.';
            return FALSE;
        }

        return parent::validateInput($error);
    }

}
