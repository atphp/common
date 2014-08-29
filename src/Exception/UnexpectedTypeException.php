<?php

namespace AndyTruong\Common\Exception;

class UnexpectedTypeException extends ValidatingException
{

    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType, is_object($value) ? get_class($value) : gettype($value)));
    }

}
