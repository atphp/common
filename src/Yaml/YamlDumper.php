<?php

namespace AndyTruong\Common\Yaml;

use AndyTruong\Common\Yaml\Dumper\PHPExtensionDumper;
use AndyTruong\Common\Yaml\Dumper\SpycDumper;
use AndyTruong\Common\Yaml\Dumper\SymfonyYamlDumper;
use RuntimeException;

class YamlDumper
{

    protected $real_dumper;

    public function getRealDumper()
    {
        if (null === $this->real_dumper) {
            $this->real_dumper = $this->detectRealDumper();
        }

        return $this->real_dumper;
    }

    protected function detectRealDumper()
    {
        if (function_exists('yaml_emit')) {
            return new PHPExtensionDumper();
        }
        elseif (function_exists('spyc_dump')) {
            return new SpycDumper();
        }
        elseif (class_exists('Symfony\\Component\\Yaml\\Dumper')) {
            return new SymfonyYamlDumper();
        }
        throw new RuntimeException('No YAML dumper found. You need install symfony/yaml library or PHP yaml extension, or mustangostang/spyc library.');
    }

    /**
     * Dumps a PHP value to YAML.
     *
     * @param mixed   $input                  The PHP value
     * @param int     $inline                 The level where you switch to inline YAML
     * @param int     $indent                 The level of indentation (used internally)
     * @param bool    $exceptionOnInvalidType true if an exception must be thrown on invalid types (a PHP resource or object), false otherwise
     * @param bool    $objectSupport          true if object support is enabled, false otherwise
     *
     * @return string  The YAML representation of the PHP value
     */
    public function dump($input, $inline = 0, $indent = 0, $exceptionOnInvalidType = false, $objectSupport = false)
    {
        return $this->getRealDumper()->dump($input, $indent, $indent, $exceptionOnInvalidType, $objectSupport);
    }

}
