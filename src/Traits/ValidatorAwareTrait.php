<?php

namespace AndyTruong\Common\Traits;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

trait ValidatorAwareTrait
{

    /**
     * Method mapping.
     *
     * @var string|string[]
     */
    protected $validator_method_mapping = 'loadValidatorMetadata';

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Generate default validator.
     *
     * @return ValidatorInterface
     */
    protected function getDefaultValidator()
    {
        $builder = new ValidatorBuilder();
        $builder->addMethodMapping($this->validator_method_mapping);
        return $builder->getValidator();
    }

    /**
     * Set validator.
     *
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get validator.
     *
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        if (null === $this->validator) {
            $this->setValidator($this->getDefaultValidator());
        }
        return $this->validator;
    }

}
