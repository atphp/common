<?php

namespace AndyTruong\Common\Yaml;

use AndyTruong\Common\Yaml\Parser\PHPExtensionParser;
use AndyTruong\Common\Yaml\Parser\SpycParser;
use AndyTruong\Common\Yaml\Parser\SymfomyYamlParser;
use AndyTruong\Common\Yaml\Parser\YamlParserInterface;
use RuntimeException;

class YamlParser
{

    /**
     * Real parser.
     *
     * @var YamlParserInterface
     */
    protected $real_parser;

    /**
     * Get the real parser.
     *
     * @return YamlParserInterface
     */
    public function getRealParser()
    {
        if (null === $this->real_parser) {
            $this->real_parser = $this->detectRealParser();
        }
        return $this->real_parser;
    }

    /**
     * Find a real YAML parser.
     *
     * @throws \RuntimeException
     */
    public function detectRealParser()
    {
        if (function_exists('yaml_parse')) {
            return new PHPExtensionParser();
        }
        elseif (function_exists('spyc_load')) {
            return new SpycParser();
        }
        elseif (class_exists('Symfony\\Component\\Yaml\\Parser')) {
            return new SymfomyYamlParser();
        }
        throw new RuntimeException('No YAML parser found. You need install symfony/yaml library or PHP yaml extension, or mustangostang/spyc library.');
    }

    /**
     * Wrapper for real parser's parse method.
     *
     * @param string  $value                  A YAML string
     * @param bool    $exceptionOnInvalidType true if an exception must be thrown on invalid types (a PHP resource or object), false otherwise
     * @param bool    $objectSupport          true if object support is enabled, false otherwise
     * @param bool    $objectForMap           true if maps should return a stdClass instead of array()
     * @return mixed  A PHP value
     */
    public function parse($value, $exceptionOnInvalidType = false, $objectSupport = false, $objectForMap = false)
    {
        return $this->getRealParser()->parse($value, $exceptionOnInvalidType, $objectSupport, $objectForMap);
    }

}
