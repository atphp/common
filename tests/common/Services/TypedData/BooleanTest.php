<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class BooleanTest extends \PHPUnit_Framework_TestCase
{

    public function testBooleanType()
    {
        $schema = array('type' => 'boolean');

        $data = at_data($schema, TRUE);
        $this->assertTrue($data->validate());
        $this->assertTrue($data->getValue());
        $this->assertFalse($data->isEmpty());

        $data = at_data($schema, FALSE);
        $this->assertTrue($data->validate());
        $this->assertFalse($data->getValue());
        $this->assertTrue($data->isEmpty());

        $data = at_data($schema, 'I am string');
        $this->assertFalse($data->validate());
        $this->assertNull($data->getValue());
    }

}
