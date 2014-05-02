<?php

namespace AndyTruong\Common\TestCases\Services;

use AndyTruong\Common\TwigFactory;
use Twig_SimpleFilter;
use Zend\EventManager\EventManager;

class TwigTest extends \PHPUnit_Framework_TestCase {
  public function testTwigFactory() {
    $this->assertInstanceOf('Twig_Environment', at_twig());
  }

  public function testFactoryOptions() {
    $em = new EventManager();
    $em->attach('at.twig.factory.options', function ($e) {
      return array('debug' => TRUE, 'foo' => 'bar');
    });
    at_event_manager('at.twig.factory', $em);

    $factory = new TwigFactory();
    $options = $factory->getOptions();
    $this->assertTrue($options['debug']);
    $this->assertEquals('bar', $options['foo']);
  }

  public function testCustomFilter() {
    $em = new EventManager();
    $em->attach('at.twig.extension.filters', function ($e) {
      $filters[] = new Twig_SimpleFilter('at_common_print', 'print_r');
      return $filters;
    });
    at_event_manager('at.twig.extension', $em);

    $this->assertInstanceOf('Twig_SimpleFilter', at_twig($refresh = TRUE)->getFilter('at_common_print'));
  }
}
