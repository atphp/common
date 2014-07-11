<?php

namespace AndyTruong\Common\Traits;

use ReflectionClass;
use RuntimeException;

/**
 * This Trait is only available when we use this library with doctrine/cache:~1.3.0.
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
    public function setCacheProvider($service, $provider, $options = [])
    {
        if (isset($this->cache_providers[$service])) {
            throw new RuntimeException('Can not override cache provider for a configured service.');
        }

        if (!$provider instanceof \Doctrine\Common\Cache\Cache) {
            $msg = sprintf('Cache provider must implement Doctrine\Common\Cache\Cache interface: %s', $provider);
            throw new RuntimeException($msg);
        }

        $this->cache_providers[$service] = $provider;
        if (!empty($options)) {
            $this->cache_providers[$service] = $options;
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
        $params = isset($this->cache_providers[$service]) ? $this->cache_providers[$service] : null;

        if (null === $params) {
            return new $class();
        }

        $reflector = new ReflectionClass($class);
        return $reflector->newInstanceArgs($params);
    }

}
