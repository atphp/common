<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\ValidatorAwareClass;
use PHPUnit_Framework_TestCase;

class ValidatorAwareTraitTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        parent::setUp();

        if (-1 === \version_compare(\phpversion(), '5.4')) {
            $this->markTestSkipped('Trait is only available in PHP 5.4');
        }
    }

    /**
     * @group atvalidate
     */
    public function testBasic()
    {
        // Check getValidator method
        $object = new ValidatorAwareClass();
        $this->assertInstanceOf('Symfony\Component\Validator\Validator\ValidatorInterface', $object->getValidator());

        // validator is injectable — check setValidator() method.
        $builder = new \Symfony\Component\Validator\ValidatorBuilder();
        $builder->addMethodMapping('simpleMethodMapping');
        $validator = $builder->getValidator();
        $object->setValidator($validator);
        $this->assertEquals(spl_object_hash($validator), spl_object_hash($object->getValidator()));
    }

}
