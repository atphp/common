<?php

namespace AndyTruong\Common\Yaml\Parser;

interface YamlParserInterface
{

    /**
     * Parses a YAML string to a PHP value.
     *
     * @param string  $value                  A YAML string
     * @param bool    $exceptionOnInvalidType true if an exception must be thrown on invalid types (a PHP resource or object), false otherwise
     * @param bool    $objectSupport          true if object support is enabled, false otherwise
     * @param bool    $objectForMap           true if maps should return a stdClass instead of array()
     *
     * @return mixed  A PHP value
     *
     * @throws ParseException If the YAML is not valid
     */
    public function parse($value, $exceptionOnInvalidType = false, $objectSupport = false, $objectForMap = false);
}
