<?php

namespace AndyTruong\Common\TestCases\Services;

class ContainerTest extends \PHPUnit_Framework_TestCase {
  /**
   * @dataProvider basicSetGetProvider
   */
  public function testBasicSetGet($value, $message) {
    $c = new \AndyTruong\Common\Container();
    $c['test_value'] = $value;
    $this->assertEquals($value, $c['test_value'], $message);
  }

  public function basicSetGetProvider() {
    return array(
      array(1, 'Number'),
      array('string', 'String'),
      array(new \DateTime(), 'object'),
    );
  }

  /**
   * @dataProvider rawSetGetProvider
   */
  public function testRaw($value, $message) {
    $c = new \AndyTruong\Common\Container();
    $c['test_value'] = $value;
    $this->assertEquals($value, $c->raw('test_value'), $message);
  }

  public function rawSetGetProvider() {
    return array(
      array(1, 'Number'),
      array('string', 'String'),
      array(new \DateTime(), 'object'),
      array(function ($foo) { return $foo; }, 'Closure')
    );
  }

  /**
   * @dataProvider basicSetGetProvider
   */
  public function testATC($value, $message) {
    atc('set', 'foo', $value);
    $this->assertEquals($value, atc('get', 'foo'));
  }

  public function testATCLazyFetching() {
    $container = atc();

    // Create event-manager and save it in event-manager container
    $em = new \Zend\EventManager\EventManager();
    $em->attach('at.container.not_found', function ($e) {
      $c = $e->getTarget();
      list($id) = $e->getParams();
      atc('set', $id, "Getting {$id}");
    });

    // Default event-manager is already set in previous test case. If service-
    // container was not initialized, we can set via event-manager container.
    // at_event_manager('at.container', $em);

    // Set event-manager to container.
    $container->setEventManager($em);

    // Access non-existing service
    $this->assertEquals('Getting testATCLazyFetching', atc('get', 'testATCLazyFetching'));
  }
}
