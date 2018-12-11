<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Api\Constraint\CallbackConstraint;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter\DateTimeParameter;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\InvalidTypeViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter;
use Chaplean\Bundle\ApiClientBundle\Exception\UnexpectedTypeException;

/**
 * Class Parameter.
 *
 * @package   Chaplean\Bundle\ApiClientBundle
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class Parameter
{
    protected $constraints = [];
    protected $optional = false;
    protected $value = null;
    protected $violations = null;

    /**
     * Parameter constructor.
     * Don't use the function directly, use the static builders to get a new Parameter.
     */
    protected function __construct()
    {
        $this->violations = new ParameterConstraintViolationCollection();
    }

    /**
     * Check wether the value of this param passes the constraints checks
     *
     * @return boolean
     */
    public function isValid()
    {
        $this->violations = $this->validate();

        return $this->violations->isEmpty();
    }

    /**
     * @return ParameterConstraintViolationCollection
     */
    protected function validate()
    {
        if ($this->optional && $this->value === null) {
            return $this->violations;
        }

        foreach ($this->constraints as $constraint) {
            $constraint->validate($this->value, $this->violations);
        }

        return $this->violations;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->violations = new ParameterConstraintViolationCollection();

        $this->value = $value;
    }

    /**
     * Transform this parameter into its array value
     *
     * @return mixed
     */
    protected function parameterToArray()
    {
        return $this->value;
    }

    /**
     * @return ParameterConstraintViolationCollection
     */
    public function getViolations()
    {
        return $this->violations;
    }

    /**
     * Mark the value as being optional, a.k.a. skip serialization and validation if null
     *
     * @return Parameter
     */
    public function optional()
    {
        $this->optional = true;

        return $this;
    }

    /**
     * Default value if the param is missing, this value still has to pass the constraints
     *
     * @param mixed $value
     *
     * @return Parameter
     */
    public function defaultValue($value)
    {
        $this->setValue($value);

        return $this;
    }

    /**
     * @param Constraint|callable $constraint
     *
     * @return void
     */
    public function addConstraint($constraint)
    {
        if (is_callable($constraint)) {
            $constraint = new CallbackConstraint($constraint);
        }

        if (!$constraint instanceof Constraint) {
            throw new UnexpectedTypeException($constraint, Constraint::class . ' or a callable');
        }

        $this->constraints[] = $constraint;
    }

    /**
     * Construct a Parameter configured to be an untyped value
     *
     * @return Parameter
     */
    public static function untyped()
    {
        $param = new Parameter();

        $param->addConstraint(function($value, ParameterConstraintViolationCollection $violations) {
            if ($value === null) {
                $violations->add(new MissingParameterViolation(''));
            } else if (!is_bool($value) && !is_int($value) && !is_float($value) && !is_string($value)) {
                $violations->add(new InvalidTypeViolation($value, 'untyped'));
            }
        });

        return $param;
    }

    /**
     * Construct a Parameter configured to be a boolean
     *
     * @return Parameter
     */
    public static function bool()
    {
        $param = new Parameter();

        $param->addConstraint(function($value, ParameterConstraintViolationCollection $violations) {
            if ($value === null) {
                $violations->add(new MissingParameterViolation(''));
            } else if (!is_bool($value)) {
                $violations->add(new InvalidTypeViolation($value, 'boolean'));
            }
        });

        return $param;
    }

    /**
     * Construct a Parameter configured to be an integer
     *
     * @return Parameter
     */
    public static function int()
    {
        $param = new Parameter();

        $param->addConstraint(function($value, ParameterConstraintViolationCollection $violations) {
            if ($value === null) {
                $violations->add(new MissingParameterViolation(''));
            } else if (!is_int($value)) {
                $violations->add(new InvalidTypeViolation($value, 'integer'));
            }
        });

        return $param;
    }

    /**
     * Construct a Parameter configured to be a float
     *
     * @return Parameter
     */
    public static function float()
    {
        $param = new Parameter();

        $param->addConstraint(function($value, ParameterConstraintViolationCollection $violations) {
            if ($value === null) {
                $violations->add(new MissingParameterViolation(''));
            } else if (!is_float($value)) {
                $violations->add(new InvalidTypeViolation($value, 'float'));
            }
        });

        return $param;
    }

    /**
     * Construct a Parameter configured to be a string
     *
     * @return Parameter
     */
    public static function string()
    {
        $param = new Parameter();

        $param->addConstraint(function($value, ParameterConstraintViolationCollection $violations) {
            if ($value === null) {
                $violations->add(new MissingParameterViolation(''));
            } else if (!is_string($value)) {
                $violations->add(new InvalidTypeViolation($value, 'string'));
            }
        });

        return $param;
    }

    /**
     * Construct a Parameter configured to be an array
     *
     * @param Parameter $parameter
     *
     * @return ArrayParameter
     */
    public static function arrayList(Parameter $parameter)
    {
        return new ArrayParameter($parameter);
    }

    /**
     * Construct a Parameter configured to be an object
     *
     * @param array[Parameter] $parameters
     *
     * @return ObjectParameter
     */
    public static function object(array $parameters)
    {
        return new ObjectParameter($parameters);
    }

    /**
     * Construct a Parameter configured to be an id (integer)
     *
     * @return Parameter
     */
    public static function id()
    {
        return self::int();
    }

    /**
     * Construct a Parameter configured to be an id (integer)
     *
     * @param string $format Optional: How to format the field when sending the request
     *
     * @return Parameter
     */
    public static function dateTime($format = 'Y-m-d')
    {
        return new DateTimeParameter($format);
    }

    /**
     * @return boolean
     */
    public function isOptional()
    {
        return $this->optional;
    }
}
