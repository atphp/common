<?php

namespace AndyTruong\Common\Fixtures;

class Person
{

    /**
     * Name of Person.
     *
     * @var string
     */
    protected $name;

    /**
     * Reference to Father.
     * @var Person
     */
    protected $father;

    public function getName()
    {
        return $this->name;
    }

    public function getFather()
    {
        return $this->father;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setFather(Person $father)
    {
        $this->father = $father;
        return $this;
    }

}
