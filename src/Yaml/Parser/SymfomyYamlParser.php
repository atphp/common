<?php

namespace AndyTruong\Common\Yaml\Parser;

use AndyTruong\Common\Yaml\Parser\YamlParserInterface;
use Symfony\Component\Yaml\Parser as BaseParser;

class SymfomyYamlParser extends BaseParser implements YamlParserInterface
{

    public function parse($value, $exceptionOnInvalidType = false, $objectSupport = false, $objectForMap = false)
    {
        return parent::parse($value, $exceptionOnInvalidType, $objectSupport, $objectForMap);
    }

}
