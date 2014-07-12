<?php

namespace AndyTruong\Common\TestCases\Services;

use AndyTruong\Common\Fixtures\Person;
use AndyTruong\Common\Serializer;
use PHPUnit_Framework_TestCase;

/**
 * @group serializer
 */
class SerializerTest extends PHPUnit_Framework_TestCase
{

    public function testDemo()
    {
        $serializer = new Serializer();

        $father = new Person();
        $father->setName('Andy T.');

        $person = new Person();
        $person->setName('James T.');
        $person->setFather($father);

        $expected = array('name' => 'James T.', 'father' => array('name' => 'Andy T.'));
        $this->AssertEquals($expected, $serializer->toArray($person));
    }

}
