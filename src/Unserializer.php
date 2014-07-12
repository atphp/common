<?php

namespace AndyTruong\Common;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use RuntimeException;
use stdClass;

/**
 * Class to unserialize an object from arary/json.
 *
 *      class Person {
 *          private $name;
 *          private $father;
 *
 *          public function getName() { return $this->name; }
 *          public function getFather() { return $this->father; }
 *          public function setName($name) { $this->name = $name; }
 *          public function setFather(Person $father) { $this->father = $father; }
 *      }
 *
 *      use AndyTruong\Common\Unserializer;
 *      $unserializer = new Unserialize();
 *
 *      // Example 1: nested unserialize
 *      $person_1_array = ['name' => 'Joshep', 'father' => ['name' => 'Jacob']];
 *      $person_1 = $unserialize->fromArray($person_1_array, 'Person');
 *
 *      // Example 2: Lazy loading
 *      $unserializer->setEntityManager($em);
 *      $person_2_array = ['name' => 'Jacob', 'father' => 1];
 *      $person_2 = $unserializer->fromArray($person_2_array, 'Person');
 *
 * @see \AndyTruong\Common\TestCases\Services\SerializerTest
 */
class Unserializer
{

    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Set entity manager.
     *
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Check existence of entity manager.
     *
     * @return boolean
     */
    public function hasEntityManager()
    {
        return null !== $this->em;
    }

    /**
     * Camelizes a given string.
     *
     * @param  string $string Some string
     * @return string The camelized version of the string
     */
    protected function camelize($string)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $string);
    }

    /**
     * Set property.
     *
     * @param string $pty
     * @param mixed $value
     * @throws \RuntimeException
     */
    public function setPropertyValue($obj, $pty, $value)
    {
        $method = 'set' . $this->camelize($pty);
        $r_class = new ReflectionClass($obj);

        if ($r_class->hasMethod($method) && $r_class->getMethod($method)->isPublic()) {
            // object's property should be an other object, cast it here
            if (($params = $r_class->getMethod($method)->getParameters()) && $params[0]->getClass()) {
                try {
                    $value = $this->convertPropertyValue($value, $params[0]->getClass()->getName());
                }
                catch (\RuntimeException $e) {
                    throw new \RuntimeException(sprintf('Can not unserialize %s.%s', get_class($obj), $pty));
                }
            }

            // Inject value to object's property
            $obj->{$method}($value);
        }
        elseif ($r_class->hasProperty($pty) && $r_class->getProperty($pty)->isPublic()) {
            $obj->{$pty} = $value;
        }
        else {
            throw new RuntimeException(sprintf('%s.%s is not writable.', get_class($obj), $pty));
        }
    }

    /**
     *
     * @param int|array $in
     * @param string $to_type
     * @return mixed
     */
    protected function convertPropertyValue($in, $to_type)
    {
        if ($this->hasEntityManager() && is_numeric($in)) {
            if ($repository = $this->getEntityManager()->getRepository($to_type)) {
                return $repository->find($in);
            }
        }

        if (is_array($in)) {
            return $this->fromArray($in, $to_type);
        }

        throw new \RuntimeException();
    }

    /**
     * Convert array to object.
     *
     * @param array $array
     * @param string $class_name
     * @return stdClass
     */
    public function fromArray($array, $class_name)
    {
        $obj = new $class_name;

        foreach ($array as $pty => $value) {
            $this->setPropertyValue($obj, $pty, $value);
        }

        return $obj;
    }

    /**
     * Convert json string to object.
     *
     * @param string $json
     * @param string $class_name
     */
    public function fromJSON($json, $class_name)
    {
        return $this->fromArray(json_decode($json), $class_name);
    }

}
