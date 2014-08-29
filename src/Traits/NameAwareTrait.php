<?php

namespace AndyTruong\Common\Traits;

use RuntimeException;

trait NameAwareTrait
{

    /** @var string */
    protected $name;

    /** @var string */
    protected $humanName;

    /**
     * {@inheritdoc}
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        if (empty($this->name)) {
            throw new RuntimeException('Name can not be empty.');
        }
        return $this->name;
    }

    /**
     * Set human-name.
     *
     * @param string $label
     * @return self
     */
    public function setHumanName($label)
    {
        $this->humanName = $label;
        return $this;
    }

    public function getHumanName()
    {
        if (empty($this->humanName)) {
            throw new RuntimeException('Human name can not be empty.');
        }
        return $this->humanName;
    }

}
