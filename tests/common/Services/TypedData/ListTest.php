<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class ListTest extends \PHPUnit_Framework_TestCase
{

    public function testListType()
    {
        $schema = array('type' => 'list');

        $input = array();
        $input[] = array(NULL, TRUE, 1, 'one', array('one'), at_id(new \stdClass()));
        $input[] = array('One', 'Two', 'Three');
        foreach ($input as $in) {
            $data = at_data($schema, $in);
            $this->assertTrue($data->validate($error));
            $this->assertEquals($in, $data->getValue());
        }
    }

    public function testListStrictType()
    {
        $schema = array('type' => 'list<integer>');

        $data = at_data($schema, array(1, 2));
        $this->assertTrue($data->validate());
        $this->assertEquals(array(1, 2), $data->getValue());

        $data = at_data($schema, array(1, 'Two'));
        $this->assertFalse($data->validate($error));
    }

}
