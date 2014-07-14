<?php

namespace AndyTruong\Common\Traits\Application;

use Doctrine\ORM\EntityManager;

trait ORMAwareTrait
{

    /**
     * @var EntityManager
     */
    protected $entity_manager;

    /**
     * @var string[] Entity directories.
     */
    protected $entity_directories = [];

    /**
     *
     * @param type $name
     * @param \AndyTruong\Common\Traits\Application\EntityManagerInterface $em
     */
    public function setEntityManager($name, EntityManagerInterface $em)
    {
        new \Doctrine\Common\Persistence\Mapping\MappingException;
    }

}
