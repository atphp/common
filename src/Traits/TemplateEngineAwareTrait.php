<?php

namespace AndyTruong\Common\Traits;

use Symfony\Component\Templating\EngineInterface;

/**
 * This trait is only available when we require this library with symfony/templating:~2.5.0
 */
trait TemplateEngineAwareTrait
{

    /** @var EngineInterface */
    protected $templateEngine;

    /**
     * Inject template engine.
     *
     * @param EngineInterface $templateEngine
     */
    public function setTemplateEngine(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * Get template engine
     *
     * @return EngineInterface
     */
    public function getTemplateEngine()
    {
        return $this->templateEngine;
    }

}
