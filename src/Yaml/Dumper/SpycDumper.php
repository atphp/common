<?php

namespace AndyTruong\Common\Yaml\Dumper;

class SpycDumper implements YamlDumperInterface
{

    public function dump($input, $inline = 0, $indent = 0, $exceptionOnInvalidType = false, $objectSupport = false)
    {
        $spyc = new \Spyc();
        return $spyc->dump($input, false, false);
    }

}
