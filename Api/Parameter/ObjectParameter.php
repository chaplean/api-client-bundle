<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\ExtraDataViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\InvalidTypeViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotObjectViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\RequireAtLeastOneOfViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\RequireExactlyOneOfViolation;
use Chaplean\Bundle\ApiClientBundle\Exception\RequiredParametersOneOfException;

/**
 * Class ObjectParameter.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ObjectParameter extends Parameter
{
    /**
     * @var array|Parameter[]
     */
    protected $requireAtLeastParameters;

    /**
     * @var array|Parameter[]
     */
    protected $requireExactlyParameters;

    /**
     * @var array|Parameter[]
     */
    protected $parameters;

    /**
     * ObjectParameter constructor.
     *
     * @param array $parameters
     */
    protected function __construct(array $parameters)
    {
        parent::__construct();

        $this->parameters = $parameters;
        $this->requireExactlyParameters = array();
        $this->requireAtLeastParameters = array();

        $this->defaultValue([]);

        if (count($parameters) > 0) {
            $defaults = [];
            $optionals = [];
            foreach ($parameters as $key => $value) {
                $defaultValue = $value->value;
                $optional = $value->optional;

                if ($defaultValue !== null) {
                    $defaults[$key] = $defaultValue;
                }

                if ($optional) {
                    $optionals[] = $key;
                }
            }

            $defaults = count($defaults) > 0 ? $defaults : null;
            $this->defaultValue($defaults);

            if (count($optionals) === count($parameters)) {
                $this->optional = true;
            }
        }

        $this->addConstraint(
            function ($value, ParameterConstraintViolationCollection $violations) {
                if ($value === null) {
                    $violations->add(new MissingParameterViolation(''));
                } else {
                    if (!is_array($value)) {
                        $violations->add(new InvalidTypeViolation($value, 'array'));
                    } else {
                        foreach (array_keys($value) as $key) {
                            if (!is_string($key)) {
                                $violations->add(new NotObjectViolation());
                                break;
                            }
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

        $extraKeys = array_diff(array_keys($this->value), array_keys($this->parameters));
        if (!empty($extraKeys)) {
            $violations->add(new ExtraDataViolation($extraKeys));
        }

        foreach ($this->parameters as $key => $parameter) {
            /** @var Parameter $value */
            $value = $this->value[$key] ?? null;

            if (!$parameter->isOptional() || $value !== null) {
                if ($value !== null) {
                    $violations->addChild((string) $key, $value->validate());
                } else {
                    if (!in_array($key, array_merge($this->requireAtLeastParameters, $this->requireExactlyParameters))) {
                        $violations->add(new MissingParameterViolation($key));
                    }
                }
            }
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
            if (!$this->value) {
                $this->value = [];
            }

            foreach ($values as $key => $value) {
                $parameter = $this->parameters[$key] ?? null;

                if ($parameter !== null) {
                    $parameter = clone $parameter;
                    $parameter->setValue($value);
                    $this->value[$key] = $parameter;
                } else {
                    $this->value[$key] = null;
                }
            }
        } else {
            $this->value = null;
        }
    }

    /**
     * Transform this parameter into its array value
     *
     * @return array
     */
    protected function parameterToArray()
    {
        $result = [];

        if (!$this->value) {
            return $result;
        }

        /**
         * @var integer   $key
         * @var Parameter $parameter
         */
        foreach ($this->value as $key => $parameter) {
            if ($parameter->value !== null) {
                $result[$key] = $parameter->parameterToArray();
            }
        }

        return $result;
    }

    /**
     * Test if all require parameters are present in object
     *
     * @param array $requireParameters
     *
     * @return void
     */
    private function isRequireParameters(array $requireParameters)
    {
        foreach ($requireParameters as $requestParam) {
            if (!array_key_exists($requestParam, $this->parameters)) {
                throw new RequiredParametersOneOfException();
            }
        }
    }

    /**
     * @param array $parameters
     *
     * @return self
     */
    public function requireAtLeastOneOf(array $parameters)
    {
        $this->isRequireParameters($parameters);
        $this->requireAtLeastParameters = $parameters;

        foreach ($parameters as $param) {
            $this->parameters[$param]->optional();
        }

        $this->addConstraint(
            function ($value, ParameterConstraintViolationCollection $violations) {
                foreach ($this->requireAtLeastParameters as $parameter) {
                    /** @var Parameter $valueParameter */
                    $valueParameter = $value[$parameter] ?? null;

                    if ($valueParameter !== null && $valueParameter->isValid()) {
                        return null;
                    }
                }

                $violations->add(new RequireAtLeastOneOfViolation());
            }
        );

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return self
     */
    public function requireExactlyOneOf(array $parameters)
    {
        $this->isRequireParameters($parameters);
        $this->requireExactlyParameters = $parameters;

        foreach ($parameters as $param) {
            $this->parameters[$param]->optional();
        }

        $this->addConstraint(
            function ($value, ParameterConstraintViolationCollection $violations) {
                $count = 0;

                foreach ($this->requireExactlyParameters as $parameter) {
                    /** @var Parameter $valueParameter */
                    $valueParameter = $value[$parameter] ?? null;

                    if ($valueParameter !== null && $valueParameter->isValid()) {
                        $count++;
                    }
                }

                if ($count !== 1) {
                    $violations->add(new RequireExactlyOneOfViolation());
                }
            }
        );

        return $this;
    }
}
