<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\Application\DemoApplication;
use AndyTruong\Common\TestCases\Traits\TraitTestCase;
use AndyTruong\Common\Traits\Application\BaseApplication;

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
        $config_file = dirname(dirname(__DIR__)) . '/fixtures/Traits/Application/config/config.yml';
        return new BaseApplication($config_file);
    }

    public function testServices()
    {
        $app = $this->getApplication();
        $this->assertInstanceof('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface', $app->getDispatcher());
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
