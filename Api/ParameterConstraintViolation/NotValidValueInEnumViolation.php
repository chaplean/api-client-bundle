<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

/**
 * Class NotValidValueInEnumViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class NotValidValueInEnumViolation extends ParameterConstraintViolation
{
    /**
     * NotContainsViolation constructor.
     *
     * @param mixed $actualValue
     * @param array $array
     */
    public function __construct($actualValue, array $array)
    {
        parent::__construct(
            sprintf(
                '%s (%s) is not valid. Allowed values: [%s]',
                $actualValue,
                gettype($actualValue),
                implode(
                    ', ',
                    array_map(
                        function ($element) {
                            return sprintf('%s (%s)', $element, gettype($element));
                        },
                        $array
                    )
                )
        ));
    }
}
