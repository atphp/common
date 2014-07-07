<?php

namespace AndyTruong\Common\Yaml\Dumper;

class SpycDumper implements YamlDumperInterface
{

    public function dump($input, $inline = 0, $indent = 0, $exceptionOnInvalidType = false, $objectSupport = false)
    {
        return spyc_dump($input);
    }

}
