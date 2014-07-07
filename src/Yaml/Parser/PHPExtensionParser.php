<?php

namespace AndyTruong\Common\Yaml\Parser;

class PHPExtensionParser implements YamlParserInterface
{

    public function parse($value, $exceptionOnInvalidType = false, $objectSupport = false, $objectForMap = false)
    {
        return yaml_parse($value);
    }

}
