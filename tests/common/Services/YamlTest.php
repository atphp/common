<?php

namespace AndyTruong\Common\TestCases\Services;

use AndyTruong\Common\Yaml\YamlParser;
use PHPUnit_Framework_TestCase;

/**
 * @group yaml
 */
class YamlTest extends PHPUnit_Framework_TestCase
{

    public function testParser()
    {
        $parser = new YamlParser();
        $this->assertInstanceOf('AndyTruong\Common\Yaml\Parser\YamlParserInterface', $parser->getRealParser());
        $this->assertEquals(array('key' => 'value'), $parser->parse('{ "key": "value" }'));
        $this->assertEquals(array('name' => 'Drupal', 'age' => 13), $parser->parse('{ name: Drupal, age: 13 }'));
    }

    public function testDumper()
    {
        $dumper = new \AndyTruong\Common\Yaml\YamlDumper();
        $this->assertInstanceOf('AndyTruong\Common\Yaml\Dumper\YamlDumperInterface', $dumper->getRealDumper());
        $this->assertEquals('{ key: value }', $dumper->dump(array('key' => 'value')));
        $this->assertEquals('{ name: Drupal, age: 13 }', $dumper->dump(array('name' => 'Drupal', 'age' => 13)));
    }

}
