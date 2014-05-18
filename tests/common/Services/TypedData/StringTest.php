<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class StringTest extends \PHPUnit_Framework_TestCase
{

    public function testStringType()
    {
        $schema = array('type' => 'string');

        $input = 'I am string';
        $data = at_data($schema, $input);
        $this->assertTrue($data->validate());
        $this->assertEquals($input, $data->getValue());
        $this->assertFalse($data->isEmpty());

        $data = at_data($schema, '');
        $this->assertTrue($data->validate());
        $this->assertEquals('', $data->getValue());
        $this->assertTrue($data->isEmpty());

        $data = at_data($schema, array('I am array'));
        $this->assertFalse($data->validate());
        $this->assertNull($data->getValue());
    }

}
