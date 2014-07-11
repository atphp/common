<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\PersonEntity;

class EntitiyTraitTest extends TraitTestCase
{

    /**
     * @group atdev
     */
    public function testFromToArray()
    {
        $father = PersonEntity::fromArray(array('name' => 'Andy T'));
        $person = PersonEntity::fromArray(array('name' => 'James T', 'father' => $father));

        $this->assertInstanceOf('AndyTruong\Common\Fixtures\Traits\PersonEntity', $father);
        $this->assertInstanceOf('AndyTruong\Common\Fixtures\Traits\PersonEntity', $person);

        $this->assertEquals(array(
                'name' => 'James T',
                'father' => array('name' => 'Andy T')
            ), $person->toArray()
        );

        $this->assertEquals(array(
                'name' => 'James T',
                'father' => array('name' => 'Andy T')
            ), $person->toArray(false)
        );
    }

    /**
     * @group indev
     */
    public function testFromToJSON()
    {
        $andyt = '{ "name": "Andy T" }';
        $jamest = '{ "name": "James T", "father": { "name": "Andy T" } }';

        $father = PersonEntity::fromJSON($andyt);
        $person = PersonEntity::fromJSON($jamest);

        $this->assertInstanceOf('AndyTruong\Common\Fixtures\Traits\PersonEntity', $father);
        $this->assertInstanceOf('AndyTruong\Common\Fixtures\Traits\PersonEntity', $person);
        $this->assertInstanceOf('AndyTruong\Common\Fixtures\Traits\PersonEntity', $person->getFather());

        $this->assertJsonStringEqualsJsonString($andyt, $father->toJSON(false));
        $this->assertJsonStringEqualsJsonString($jamest, $person->toJSON(false));
    }

}
