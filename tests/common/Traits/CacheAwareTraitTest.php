<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\CacheAwareClass;
use PHPUnit_Framework_TestCase;

class CacheAwareTraitTest extends PHPUnit_Framework_TestCase
{

    public function testDefaultProvider()
    {
        $obj = new CacheAwareClass();
        $this->assertInstanceOf('Doctrine\Common\Cache\Cache', $obj->getCacheProvider());
    }

    public function testSetProvider()
    {
        $obj = new CacheAwareClass();
        $obj->setCacheProvider('custom_service', 'Doctrine\\Common\\Cache\\FilesystemCache', ['/tmp/at-common']);
        $this->assertInstanceOf('Doctrine\Common\Cache\Cache', $obj->getCacheProvider('custom_service'));
        $this->assertInstanceOf('Doctrine\Common\Cache\FilesystemCache', $obj->getCacheProvider('custom_service'));
        return $obj;
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Can not override cache provider for a configured service: custom_service.
     */
    public function testUnexpectedSetProvider()
    {
        $obj = $this->testSetProvider();
        $obj->setCacheProvider('custom_service', 'Doctrine\\Common\\Cache\\ArrayCache');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Cache provider must implement Doctrine\Common\Cache\Cache interface: DateTime.
     * @group cache
     */
    public function testWrongProviderInterface()
    {
        $obj = $this->testSetProvider();
        $obj->setCacheProvider('custom_service', 'DateTime');
    }

}
