<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class IntegerTest extends \PHPUnit_Framework_TestCase
{

    public function testIntegerType()
    {
        $schema = array('type' => 'integer');

        $data = at_data($schema, 1);
        $this->assertTrue($data->validate());
        $this->assertEquals(1, $data->getValue());
        $this->assertFalse($data->isEmpty());

        $data = at_data($schema, -1);
        $this->assertTrue($data->validate());
        $this->assertEquals(-1, $data->getValue());
        $this->assertFalse($data->isEmpty());

        $data = at_data($schema, 0);
        $this->assertTrue($data->validate());
        $this->assertEquals(0, $data->getValue());
        $this->assertTrue($data->isEmpty());

        $data = at_data($schema, 'I am string');
        $this->assertFalse($data->validate());
        $this->assertNull($data->getValue());
    }

}
