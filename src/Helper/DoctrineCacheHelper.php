<?php

namespace AndyTruong\Common\Helper;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\XcacheCache;
use Redis;
use RuntimeException;

/**
 * Helper class to get Doctrine cache implementation:
 *
 * // APC cache
 * (new DoctrineCacheHelper())->get('apc');
 *
 * // Filesystem cache
 * (new DoctrineCacheHelper())->get('filesystem', ['path' => '/tmp/cache']);
 */
class DoctrineCacheHelper
{

    /**
     * @param string $driver
     * @param array $cacheOptions
     * @return Cache
     * @throws RuntimeException
     */
    public function get($driver, array $cacheOptions = [])
    {
        switch ($driver) {
            case 'array':
            case 'apc':
            case 'xcache':
            case 'memcache':
            case 'memcached':
            case 'filesystem':
            case 'redis':
                $method = at_camelize('get_%s_implementation');
                return $this->{$method}($cacheOptions);
            default:
                throw new RuntimeException(sprintf("Unsupported cache type `%s` specified", $driver));
        }
    }

    public function getArrayImplementation()
    {
        return new ArrayCache();
    }

    public function getApcImplementation()
    {
        return new ApcCache();
    }

    public function getXcacheImplementation()
    {
        return new XcacheCache();
    }

    public function getFilesystemImplementation($cacheOptions)
    {
        if (empty($cacheOptions['path'])) {
            throw new RuntimeException('FilesystemCache path not defined');
        }
        return new FilesystemCache($cacheOptions['path']);
    }

    public function getRedisImplementation($cacheOptions)
    {
        if (empty($cacheOptions['host']) || empty($cacheOptions['port'])) {
            throw new RuntimeException('Host and port options need to be specified for redis cache');
        }

        $redis = new Redis();
        $redis->connect($cacheOptions['host'], $cacheOptions['port']);

        $cache = new RedisCache();
        $cache->setRedis($redis);

        return $cache;
    }

}
