<?php

namespace AndyTruong\Common\Fixtures\Traits;

class PersonEntity
{

    use \AndyTruong\Common\Traits\EntityTrait;

    /**
     * Name of Person.
     *
     * @var string
     */
    protected $name;

    /**
     * Reference to Father.
     * @var PersonEntity
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

    public function setFather(PersonEntity $father)
    {
        $this->father = $father;
        return $this;
    }

}
