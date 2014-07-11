<?php

namespace AndyTruong\Common\Yaml\Parser;

use AndyTruong\Common\Yaml\Parser\YamlParserInterface;
use Spyc;

class SpycParser implements YamlParserInterface
{

    public function parse($value, $exceptionOnInvalidType = false, $objectSupport = false, $objectForMap = false)
    {
        return Spyc::YAMLLoadString($value);
    }

}
