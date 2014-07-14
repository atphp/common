<?php

namespace AndyTruong\Common\Fixtures\Traits\Application;

class DemoApplication
{

    use \AndyTruong\Common\Traits\Application\ConfigTrait;

    public function __construct($config_file = null)
    {
        $this->setConfigFile($config_file ? $config_file : __DIR__ . '/config/config.yml');
    }

}
