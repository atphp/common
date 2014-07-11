<?php

namespace AndyTruong\Common\Traits;

use ReflectionClass;
use RuntimeException;

/**
 * This Trait is only available when we use this library with doctrine/cache:~1.3.0.
 *
 * Use this if you would like to have cache functionality for you class.
 *
 * Example:
 *
 *  class MyClass {
 *      use AndyTruong\Common\Traits\CacheAwareTrait;
 *
 *      public function myAction() {
 *          $cache = $this->getCacheProvider();
 *          $cache->get(…);
 *      }
 *  }
 */
trait CacheAwareTrait
{

    /**
     * @var array
     */
    protected $cache_providers = ['default' => 'Doctrine\\Common\\Cache\\ArrayCache'];

    /**
     * Options for cache providers.
     *
     * @var array
     */
    protected $cache_providers_options;

    /**
     * Config cache provider for a specific service.
     *
     * @param string $service
     * @param string $provider
     * @param array $options
     * @throws \RuntimeException
     */
    public function setCacheProvider($service, $provider, array $options = [])
    {
        if (!in_array('Doctrine\Common\Cache\Cache', class_implements($provider))) {
            $msg = sprintf('Cache provider must implement Doctrine\Common\Cache\Cache interface: %s.', $provider);
            throw new RuntimeException($msg);
        }

        if (isset($this->cache_providers[$service])) {
            $msg = sprintf('Can not override cache provider for a configured service: %s.', $service);
            throw new RuntimeException($msg);
        }

        $this->cache_providers[$service] = $provider;
        if (!empty($options)) {
            $this->cache_providers_options[$service] = $options;
        }
    }

    /**
     * Remove a cache-provider setting for a service.
     *
     * @param string $service
     */
    public function removeCacheProvider($service)
    {
        if (isset($this->cache_providers[$service])) {
            unset($this->cache_providers[$service]);
        }

        if (isset($this->cache_providers_options[$service])) {
            unset($this->cache_providers_options[$service]);
        }
    }

    /**
     * Get a cache provider for a service.
     *
     * @param string $service
     */
    public function getCacheProvider($service = 'default')
    {
        $class = isset($this->cache_providers[$service]) ? $this->cache_providers[$service] : $this->cache_providers['default'];
        $params = isset($this->cache_providers_options[$service]) ? $this->cache_providers_options[$service] : null;

        if (null === $params) {
            return new $class();
        }

        return (new ReflectionClass($class))->newInstanceArgs($params);
    }

}
