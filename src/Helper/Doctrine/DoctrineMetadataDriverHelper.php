<?php

namespace AndyTruong\Common\Helper\Doctrine;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\Mapping\Driver\StaticPHPDriver;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use InvalidArgumentException;

class DoctrineMetadataDriverHelper
{

    public function getChainDriver(Configuration $config, $mappings)
    {
        $chain = new MappingDriverChain();
        foreach ($mappings as $entity) {
            $driver = $this->getDriver($config, $entity);
            $chain->addDriver($driver, $entity['namespace']);
        }
        return $chain;
    }

    private function getDriver(Configuration $config, $entity)
    {
        switch ($entity['type']) {
            case 'annotation':
                return $config->newDefaultAnnotationDriver((array) $entity['path'], true);
            case 'yml':
                return new YamlDriver($entity['path']);
            case 'simple_yml':
                return new SimplifiedYamlDriver(array($entity['path'] => $entity['namespace']));
            case 'xml':
                return new XmlDriver($entity['path']);
            case 'simple_xml':
                return new SimplifiedXmlDriver(array($entity['path'] => $entity['namespace']));
            case 'php':
                return new StaticPHPDriver($entity['path']);
        }

        throw new InvalidArgumentException(sprintf('"%s" is not a recognized driver', $type));
    }

}
