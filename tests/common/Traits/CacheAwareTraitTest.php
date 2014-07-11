<?php

namespace AndyTruong\Common\TestCases\Traits;

use AndyTruong\Common\Fixtures\Traits\CacheAwareClass;
use Symfony\Component\Validator\Exception\RuntimeException;

class CacheAwareTraitTest extends TraitTestCase
{

    public function testDefaultProvider()
    {
        $obj = new CacheAwareClass();
        $this->assertInstanceOf('Doctrine\Common\Cache\Cache', $obj->getCacheProvider());
    }

    public function testSetProvider()
    {
        $obj = new CacheAwareClass();
        $obj->setCacheProvider('custom_service', 'Doctrine\\Common\\Cache\\FilesystemCache', array('/tmp/at-common'));
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
     */
    public function testWrongProviderInterface()
    {
        $obj = $this->testSetProvider();
        $obj->setCacheProvider('custom_service', 'DateTime');
    }

}
