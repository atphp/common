<?php

namespace AndyTruong\Common\TestCases\Traits;

use PHPUnit_Framework_TestCase;

class TraitTestCase extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        parent::setUp();

        if (-1 === \version_compare(\phpversion(), '5.4')) {
            $this->markTestSkipped('Trait is only available in PHP 5.4');
        }
    }

}
