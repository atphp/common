<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class AnonymousValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testAnonymousValidator()
    {
        $schema = array('type' => 'any');
        $schema['validate'][] = function($input, &$error = '') {
            if (!is_numeric($input) || 1 !== $input) {
                $error = 'I only accept 1';
                return FALSE;
            }
            return TRUE;
        };

        $data = at_data($schema, 0);
        $this->assertFalse($data->validate($error));
        $this->assertEquals('I only accept 1', $error);

        $data = at_data($schema, 1);
        $this->assertTrue($data->validate());
    }

}
