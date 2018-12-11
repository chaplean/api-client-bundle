<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

/**
 * Class ParameterConstraintViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ParameterConstraintViolation extends \InvalidArgumentException implements \JsonSerializable
{
    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getMessage();
    }
}
