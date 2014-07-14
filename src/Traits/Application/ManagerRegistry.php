<?php

namespace AndyTruong\Common\Traits\Application;

use Doctrine\Common\Persistence\AbstractManagerRegistry;

/**
 * WIP, see
 *  - http://php-and-symfony.matthiasnoback.nl/2014/05/inject-the-manager-registry-instead-of-the-entity-manager/
 *  - https://github.com/cranberyxl/DoctrineManagerRegistryServiceProvider
 *  - http://www.doctrine-project.org/api/common/2.4/class-Doctrine.Common.Persistence.ManagerRegistry.html
 */
class ManagerRegistry extends AbstractManagerRegistry
{

    protected function getService($name)
    {

    }

    protected function resetService($name)
    {

    }

    public function getAliasNamespace($alias)
    {

    }

}
