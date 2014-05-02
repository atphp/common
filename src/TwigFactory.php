<?php
namespace AndyTruong\Common;

use Twig_Environment;
use AndyTruong\Common\TwigExtension;
use AndyTruong\Common\EventAware;

/**
 * Class to create Twig enviroment object.
 *
 * @event at.twig.factory.options Twig-environment options are alterable on this event.
 * @event at.twig.factory.init    On Twig-environment initialized.
 * @see at_twig()
 */
class TwigFactory extends EventAware {
  /**
   * Name of event-manager.
   *
   * @var string
   */
  protected $em_name = 'twig.factory';

  protected function getOptions() {
    $options = array(
      'debug' => FALSE,
      'auto_reload' => FALSE,
      'autoescape' => FALSE,
      'cache' => '/tmp',
    );

    $this->getEventManager()->trigger('at.twig.factory.options', $this, $options);

    return $options;
  }

  /**
   * Get main Twig extension.
   */
  public function getTwigExtension() {
    return new TwigExtension();
  }

  /**
   * Get Twig enviroment object.
   *
   * @return \Twig_Environment
   */
  public function getTwigEnvironment() {
    $twig = new Twig_Environment(NULL, $this->getOptions());
    $twig->addExtension($this->getTwigExtension());
    $this->getEventManager()->trigger('at.twig.factory.options', $twig);
    return $twig;
  }
}
