<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\ValidatorAwareClass;
use Symfony\Component\Validator\ValidatorBuilder;

class ValidatorAwareTraitTest extends TraitTestCase
{

    /**
     * @group atvalidate
     */
    public function testBasic()
    {
        // Check getValidator method
        $object = new ValidatorAwareClass();
        $this->assertInstanceOf('Symfony\Component\Validator\Validator\ValidatorInterface', $object->getValidator());

        // validator is injectable — check setValidator() method.
        $builder = new ValidatorBuilder();
        $builder->addMethodMapping('simpleMethodMapping');
        $validator = $builder->getValidator();
        $object->setValidator($validator);
        $this->assertEquals(spl_object_hash($validator), spl_object_hash($object->getValidator()));
    }

}
