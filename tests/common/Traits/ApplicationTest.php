<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\Application\DemoApplication;
use AndyTruong\Common\TestCases\Traits\TraitTestCase;

/**
 * @group app
 */
class ApplicationTest extends TraitTestCase
{

    /**
     * @return DemoApplication
     */
    public function getApplication()
    {
        return new DemoApplication();
    }

    public function testGetSet()
    {
        $app = $this->getApplication();
        $this->assertEquals('FOO', $app->getConfiguration('foo', 'No default value'));
        $this->assertEquals('Default value', $app->getConfiguration('anything', 'Default value'));

        $app->setConfiguration('new', 'NEW');
        $this->assertEquals('NEW', $app->getConfiguration('new'));
    }

}
