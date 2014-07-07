<?php

namespace AndyTruong\Common\Yaml\Dumper;

interface YamlDumperInterface
{

    /**
     * Dumps a PHP value to YAML.
     *
     * @param mixed   $input                  The PHP value
     * @param int     $inline                 The level where you switch to inline YAML
     * @param int     $indent                 The level of indentation (used internally)
     * @param bool    $exceptionOnInvalidType true if an exception must be thrown on invalid types (a PHP resource or object), false otherwise
     * @param bool    $objectSupport          true if object support is enabled, false otherwise
     * @return string  The YAML representation of the PHP value.
     */
    public function dump($input, $inline = 0, $indent = 0, $exceptionOnInvalidType = false, $objectSupport = false);
}
