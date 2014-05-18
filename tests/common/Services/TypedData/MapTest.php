<?php

namespace AndyTruong\Common\TestCases\Services\TypedData;

class MapTest extends \PHPUnit_Framework_TestCase
{

    public function testMappingType()
    {
        $schema = array(
            'type' => 'mapping',
            'mapping' => array(
                'title' => array('type' => 'string'),
                'access arguments' => array('type' => 'list<string>'),
                'page callback' => array('type' => 'function'),
                'page arguments' => array('type' => 'list<string>'),
                'type' => array('type' => 'constant'),
            )
        );

        $input = array(
            'title' => 'Menu item',
            'access arguments' => array('access content'),
            'page callback' => 't',
            'page arguments' => array('Drupal'),
            'type' => 'MENU_NORMAL_ITEM',
        );

        $data = at_data($schema, $input);

        $this->assertTrue($data->validate());
        $result = $data->getValue();

        $this->assertEquals($input['title'], $result['title']);
        $this->assertEquals($input['access arguments'], $result['access arguments']);
        $this->assertEquals($input['page callback'], $result['page callback']);
        $this->assertEquals($input['page arguments'], $result['page arguments']);
        $this->assertEquals(constant('MENU_NORMAL_ITEM'), $result['type']);
    }

    public function testMappingWrongType()
    {
        $schema = array(
            'type' => 'mapping',
            'mapping' => array(
                'name' => array('type' => 'string', 'required' => TRUE),
                'girl' => array('type' => 'boolean'),
            ),
        );

        $input = array('name' => array('wrong'), 'girl' => FALSE);

        $data = at_data($schema, $input);
        $this->assertFalse($data->validate($error));
    }

    public function testMappingTypeWithRequiredProperties()
    {
        $schema = array(
            'type' => 'mapping',
            'mapping' => array(
                'name' => array('type' => 'string', 'required' => TRUE),
                'age' => array('type' => 'integer', 'required' => TRUE),
            ),
        );

        $data = at_data($schema, array('name' => 'Drupal', 'age' => 13));
        $this->assertTrue($data->validate($error));

        $data = at_data($schema, array('name' => 'Backdrop'));
        $this->assertFalse($data->validate($error));
        $this->assertEquals('Property age is required.', $error);
    }

    public function testMappingTypeWithRequiredOneOf()
    {
        $schema = array(
            'type' => 'mapping',
            'require_one_of' => array('name', 'id'),
            'mapping' => array(
                'branch' => array('type' => 'string'),
                'name' => array('type' => 'string'),
                'id' => array('type' => 'integer'),
            ),
        );

        $data = at_data($schema, array('name' => 'go_support'));
        $this->assertTrue($data->validate($error));

        $data = at_data($schema, array('id' => 1));
        $this->assertTrue($data->validate($error));

        $data = at_data($schema, array('branch' => 'Acquia'));
        $this->assertFalse($data->validate($error));
        $this->assertTrue(FALSE !== strpos($error, 'Missing one of  required keys: '));
    }

    public function testMappingTypeWithAllowExtraProperties()
    {
        $schema = array(
            'type' => 'mapping',
            'mapping' => array(
                'name' => array('type' => 'string'),
                'age' => array('type' => 'integer'),
                'country' => array('type' => 'string')
            ),
            'allow_extra_properties' => FALSE,
        );

        $data = at_data($schema, array('name' => 'Drupal', 'age' => 13, 'city' => 'Paris'));
        $this->assertFalse($data->validate($error));
        $this->assertEquals('Unexpected key found: city.', $error);
    }

}
