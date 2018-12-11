<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\InvalidTypeViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotArrayViolation;

/**
 * Class ArrayParameter.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ArrayParameter extends Parameter
{
    /**
     * @var Parameter
     */
    protected $parameter;

    /**
     * ArrayParameter constructor.
     *
     * @param Parameter $parameter
     */
    protected function __construct(Parameter $parameter)
    {
        parent::__construct();

        $this->parameter = $parameter;

        $defaultValue = $parameter->value;
        if ($defaultValue !== null) {
            $this->defaultValue([$defaultValue]);
        }

        $this->addConstraint(
            function ($value, ParameterConstraintViolationCollection $violations) {
                if ($value === null) {
                    $violations->add(new MissingParameterViolation(''));
                } elseif (!is_array($value)) {
                    $violations->add(new InvalidTypeViolation($value, 'array'));
                } else {
                    foreach (array_keys($value) as $key) {
                        if (!is_numeric($key)) {
                            $violations->add(new NotArrayViolation());
                            break;
                        }
                    }
                }
            }
        );
    }

    /**
     * @return ParameterConstraintViolationCollection
     */
    protected function validate()
    {
        $violations = parent::validate();

        if (!$violations->isEmpty() || ($this->optional && $this->value === null)) {
            return $violations;
        }

        foreach ($this->value as $key => $value) {
            $violations->addChild((string) $key, $value->validate());
        }

        return $violations;
    }

    /**
     * @param mixed $values
     *
     * @return void
     */
    public function setValue($values)
    {
        $this->violations = new ParameterConstraintViolationCollection();

        if (is_array($values)) {
            if ($this->value === null) {
                $this->value = [];
            }

            foreach ($values as $key => $value) {
                $parameter = clone $this->parameter;
                $parameter->setValue($value);
                $this->value[$key] = $parameter;
            }
        }
    }

    /**
     * Transform this parameter into its array value
     *
     * @return mixed
     */
    protected function parameterToArray()
    {
        $result = [];

        foreach ($this->value as $parameter) {
            $result[] = $parameter->parameterToArray();
        }

        return $result;
    }
}
