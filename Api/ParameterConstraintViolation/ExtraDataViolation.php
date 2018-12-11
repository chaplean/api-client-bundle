<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

/**
 * Class ExtraDataViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ExtraDataViolation extends ParameterConstraintViolation
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * ExtraDataViolation constructor.
     *
     * @param string $keys
     */
    public function __construct($keys)
    {
        parent::__construct(sprintf('Extra keys should not be present %s', json_encode($keys)));
    }

    /**
     * Get keys.
     *
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }
}
