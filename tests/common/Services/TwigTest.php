<?php

namespace AndyTruong\Common\TestCases\Services;

class TwigTest extends \PHPUnit_Framework_TestCase {
  public function testTwigFactory() {
    $this->assertInstanceOf('Twig_Environment', at_twig());
    $this->assertInstanceOf('Twig_Environment', at_twig());
  }
}
