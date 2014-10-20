<?php

/**
 * This file is part of AT Common package.
 *
 * (c) 2014-2014 thehongtt@gmail.com
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
