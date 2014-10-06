<?php

namespace AndyTruong\Common\Helper\Symfony\DI;

use InvalidArgumentException;
use IteratorIterator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * By default Symfony2 DI component only provide yaml, xml loaders, but not
 * array loader, this is why this class is here.
 */
class ArrayLoader
{

    /** @var ContainerBuilder */
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function load($content)
    {
        if (!empty($content['parameters'])) {
            foreach ($content['parameters'] as $key => $value) {
                $this->container->setParameter($key, $this->resolveServices($value));
            }
        }

        $this->parseDefinitions($content);
    }

    /**
     * Parses definitions
     *
     * @param array  $content
     */
    private function parseDefinitions($content)
    {
        if (empty($content['services'])) {
            return;
        }

        foreach ($content['services'] as $id => $service) {
            $this->parseDefinition($id, $service);
        }
    }

    /**
     * Parses a definition.
     *
     * @param string $id
     * @param array  $service
     *
     * @throws InvalidArgumentException When tags are invalid
     */
    private function parseDefinition($id, array $service)
    {
        if (is_string($service) && 0 === strpos($service, '@')) {
            $this->container->setAlias($id, substr($service, 1));

            return;
        }
        elseif (!empty($service['alias'])) {
            $public = !array_key_exists('public', $service) || (bool) $service['public'];
            $this->container->setAlias($id, new Alias($service['alias'], $public));

            return;
        }

        if (!empty($service['parent'])) {
            $definition = new DefinitionDecorator($service['parent']);
        }
        else {
            $definition = new Definition();
        }

        if (!empty($service['class'])) {
            $definition->setClass($service['class']);
        }

        if (!empty($service['scope'])) {
            $definition->setScope($service['scope']);
        }

        if (!empty($service['synthetic'])) {
            $definition->setSynthetic($service['synthetic']);
        }

        if (!empty($service['synchronized'])) {
            $definition->setSynchronized($service['synchronized']);
        }

        if (!empty($service['lazy'])) {
            $definition->setLazy($service['lazy']);
        }

        if (!empty($service['public'])) {
            $definition->setPublic($service['public']);
        }

        if (!empty($service['abstract'])) {
            $definition->setAbstract($service['abstract']);
        }

        if (!empty($service['factory_class'])) {
            $definition->setFactoryClass($service['factory_class']);
        }

        if (!empty($service['factory_method'])) {
            $definition->setFactoryMethod($service['factory_method']);
        }

        if (!empty($service['factory_service'])) {
            $definition->setFactoryService($service['factory_service']);
        }

        if (!empty($service['file'])) {
            $definition->setFile($service['file']);
        }

        if (!empty($service['arguments'])) {
            $definition->setArguments($this->resolveServices($service['arguments']));
        }

        if (!empty($service['properties'])) {
            $definition->setProperties($this->resolveServices($service['properties']));
        }

        if (!empty($service['configurator'])) {
            if (is_string($service['configurator'])) {
                $definition->setConfigurator($service['configurator']);
            }
            else {
                $definition->setConfigurator(array($this->resolveServices($service['configurator'][0]), $service['configurator'][1]));
            }
        }

        if (!empty($service['calls'])) {
            foreach ($service['calls'] as $call) {
                $args = isset($call[1]) ? $this->resolveServices($call[1]) : [];
                $definition->addMethodCall($call[0], $args);
            }
        }

        if (!empty($service['tags'])) {
            if (!is_array($service['tags']) && $service['tags'] instanceof IteratorIterator) {
                throw new InvalidArgumentException(sprintf('Parameter "tags" must be an array for service "%s".', $id));
            }

            foreach ($service['tags'] as $tag) {
                if (!isset($tag['name'])) {
                    throw new InvalidArgumentException(sprintf('A "tags" entry is missing a "name" key for service "%s".', $id));
                }

                $name = $tag['name'];
                unset($tag['name']);

                foreach ($tag as $attribute => $value) {
                    if (!is_scalar($value) && null !== $value) {
                        throw new InvalidArgumentException(sprintf('A "tags" attribute must be of a scalar-type for service "%s", tag "%s", attribute "%s".', $id, $name, $attribute));
                    }
                }

                $definition->addTag($name, $tag);
            }
        }

        if (!empty($service['decorates'])) {
            $renameId = isset($service['decoration_inner_name']) ? $service['decoration_inner_name'] : null;
            $definition->setDecoratedService($service['decorates'], $renameId);
        }

        $this->container->setDefinition($id, $definition);
    }

    /**
     * Resolves services.
     *
     * @param string $value
     * @return Reference
     */
    private function resolveServices($value)
    {
        if (is_array($value)) {
            return array_map(array($this, 'resolveServices'), $value);
        }

        if (is_string($value) && 0 === strpos($value, '@=')) {
            return new Expression(substr($value, 2));
        }

        if (is_string($value) && 0 === strpos($value, '@')) {
            if (0 === strpos($value, '@@')) {
                $value = substr($value, 1);
                $invalidBehavior = null;
            }
            elseif (0 === strpos($value, '@?')) {
                $value = substr($value, 2);
                $invalidBehavior = ContainerInterface::IGNORE_ON_INVALID_REFERENCE;
            }
            else {
                $value = substr($value, 1);
                $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
            }

            if ('=' === substr($value, -1)) {
                $value = substr($value, 0, -1);
                $strict = false;
            }
            else {
                $strict = true;
            }

            if (null !== $invalidBehavior) {
                $value = new Reference($value, $invalidBehavior, $strict);
            }
        }

        return $value;
    }

}
