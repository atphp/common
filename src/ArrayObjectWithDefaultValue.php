<?php

namespace AndyTruong\Common;

/**
 * Use this class when you need array response to all value request.
 *
 * $array = new ArrayObjectWithDefaultValue(['foo' => 'FOO']);
 * $array->setDefaultValue('BAR');
 * echo $array['foo']; // FOO
 * echo $array['bar']; // BAR — default value
 *
 * @todo Missing test case.
 */
class ArrayObjectWithDefaultValue extends ArrayObject
{

    private $defaultValue = 0;

    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function offsetExists($key)
    {
        return true;
    }

    public function offsetGet($key)
    {
        if (!parent::offsetExists($key)) {
            $this->offsetSet($key, $this->defaultValue);
        }
        return parent::offsetGet($key);
    }

}
