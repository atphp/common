<?php

namespace AndyTruong\Common\Traits\Application;

use AndyTruong\Common\Yaml\YamlParser;
use RuntimeException;
use Zend\Config\Config;
use Zend\Config\Reader\Xml;
use Zend\Config\Reader\Yaml;

trait ConfigTrait
{

    /**
     * Application configuration.
     *
     * @var Config
     */
    private $config;

    /**
     * Path to configuration file.
     *
     * @var string
     */
    private $config_file;

    public function setConfigFile($path)
    {
        $this->config_file = $path;
    }

    public function getConfigFile()
    {
        return $this->config_file;
    }

    /**
     * Parse and return new Config object.
     *
     * @return \Zend\Config\Config
     * @throws RuntimeException
     */
    private function parseConfiguration()
    {
        $filename = $this->getConfigFile();

        switch (trim(pathinfo($filename)['extension'])) {
            case 'yml':
                $reader = new Yaml(function($string) {
                    return (new YamlParser())->parse($string);
                });
                break;
            case 'xml':
                $reader = new Xml();
                break;
            case 'ini':
                $reader = new \Zend\Config\Reader\Ini();
                break;
            default:
                throw new RuntimeException(sprintf('Can not find parser for configuration file: %s', $filename));
        }

        return new Config($reader->fromFile($filename), true);
    }

    /**
     * Get configuration.
     *
     * @return Config
     * @throws RuntimeException
     */
    public function getConfigurationObject()
    {
        if (null === $this->config) {
            $this->config = $this->parseConfiguration();
        }

        return $this->config;
    }

    /**
     * Get configuration value.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getConfiguration($name, $default = null)
    {
        return $this->getConfigurationObject()->get($name, $default);
    }

    public function setConfiguration($name, $value)
    {
        return $this->getConfigurationObject()[$name] = $value;
    }

}
