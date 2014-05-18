<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class FunctionTest extends \PHPUnit_Framework_TestCase
{

    public function testFunctionType()
    {
        $schema = array('type' => 'function');

        $data = at_data($schema, 'at_id');
        $this->assertTrue($data->validate());
        $this->assertEquals('at_id', $data->getValue());

        $data = at_data($schema, 'this_is_invalid_function');
        $this->assertFalse($data->validate($error));
        $this->assertEquals('Function does not exist.', $error);

        $data = at_data($schema, array('I am array'));
        $this->assertFalse($data->validate($error));
        $this->assertEquals('Function name must be a string.', $error);
    }

}
