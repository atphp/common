<?php

namespace AndyTruong\Common\TypedData\Plugin;

class String extends Base
{

    public function isEmpty()
    {
    if (!is_null($this->input)) {
      return $this->input === '';
    }
  }

    protected function validateInput(&$error = NULL)
    {
    if (!is_string($this->input)) {
      $error = 'Input is not a string value.';
      return FALSE;
    }
    return parent::validateInput($error);
  }

}
