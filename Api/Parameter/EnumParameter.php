<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotValidValueInEnumViolation;
use Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastOneVariantException;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;

/**
 * Class EnumParameter.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api\Parameter
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class EnumParameter extends Parameter
{
    /**
     * EnumParameter constructor.
     *
     * @param mixed[] $enum
     *
     * @throws EnumRequiresAtLeastOneVariantException
     */
    protected function __construct(array $enum)
    {
        parent::__construct();

        if (empty($enum)) {
            throw new EnumRequiresAtLeastOneVariantException();
        }

        $this->addConstraint(function ($value, ParameterConstraintViolationCollection $violations) use ($enum) {
            if ($value === null) {
                $violations->add(new MissingParameterViolation(''));
            } elseif (!in_array($value, $enum, true)) {
                $violations->add(new NotValidValueInEnumViolation($value, $enum));
            }
        });
    }
}
