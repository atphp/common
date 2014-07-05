<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\PersonEntity;
use PHPUnit_Framework_TestCase;

class EntitiyTraitTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        if (-1 === \version_compare(\phpversion(), '5.4')) {
            $this->markTestSkipped('Trait is only available in PHP 5.4');
        }
    }

    public function testFromToArray()
    {
        $father = PersonEntity::fromArray(['name' => 'Andy T']);
        $person = PersonEntity::fromArray(['name' => 'James T', 'father' => $father]);

        $this->assertInstanceOf('AndyTruong\Common\Fixtures\Traits\PersonEntity', $father);
        $this->assertInstanceOf('AndyTruong\Common\Fixtures\Traits\PersonEntity', $person);

        $this->assertEquals(
            ['name' => 'James T', 'father' => ['name' => 'Andy T', 'father' => null]],
            $person->toArray()
        );
    }

}
