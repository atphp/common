<?php

namespace AndyTruong\Common\Traits\Application;

class BaseApplication
{

    use \AndyTruong\Common\Traits\EventAwareTrait,
        \AndyTruong\Common\Traits\Application\ConfigTrait,
        \AndyTruong\Common\Traits\CacheAwareTrait,
        \AndyTruong\Common\Traits\Application\ORMAwareTrait;

    public function __construct($config_file)
    {
        $this->setConfigFile($config_file);
    }

}
