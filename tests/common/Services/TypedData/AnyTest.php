<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class AnyTest extends \PHPUnit_Framework_TestCase
{

    public function testAnyType()
    {
        $schema = array('type' => 'any');

        $input = array();
        $input[] = NULL;
        $input[] = 'String';
        $input[] = array('Array Input');
        foreach ($input as $_input) {
            $data = at_data($schema, $_input);
            $this->assertTrue($data->validate());
            $this->assertEquals($_input, $data->getValue());
        }
    }

}
