<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class ConstantTest extends \PHPUnit_Framework_TestCase
{

    public function testConstantType()
    {
        $schema = array('type' => 'constant');

        if (!defined('MENU_DEFAULT_LOCAL_TASK')) {
            define('MENU_DEFAULT_LOCAL_TASK', 140);
        }

        $data = at_data($schema, 'MENU_DEFAULT_LOCAL_TASK');
        $this->assertTrue($data->validate($error));
        $this->assertEquals(constant('MENU_DEFAULT_LOCAL_TASK'), $data->getValue());

        $data = at_data($schema, 'NON_VALID_CONSTANT_THIS_IS');
        $this->assertFalse($data->validate($error));
        $this->assertEquals('Constant is not defined.', $error);
        $this->assertNull($data->getValue());

        $data = at_data($schema, 'in valid ^^');
        $this->assertFalse($data->validate($error));

        $data = at_data($schema, array('also', 'invalidate'));
        $this->assertFalse($data->validate($error));
    }

}
