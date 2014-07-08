<?php

namespace AndyTruong\Common\Fixtures\Traits;

class PersonEntity
{

    use \AndyTruong\Common\Traits\EntitiyTrait;

    /**
     * Name of Person.
     *
     * @var string
     */
    private $name;

    /**
     * Reference to Father.
     * @var PersonEntity
     */
    private $father;

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

    public function setFather(PersonEntity $father)
    {
        $this->father = $father;
        return $this;
    }

}
