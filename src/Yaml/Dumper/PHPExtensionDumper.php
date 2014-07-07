<?php

namespace AndyTruong\Common\Yaml\Dumper;

class PHPExtensionDumper implements YamlDumperInterface
{

    /**
     * {inheritdoc}
     */
    public function dump($input, $inline = 0, $indent = 0, $exceptionOnInvalidType = false, $objectSupport = false)
    {
        return yaml_emit($input);
    }

}
