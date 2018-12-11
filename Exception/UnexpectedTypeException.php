<?php

namespace Chaplean\Bundle\ApiClientBundle\Exception;

/**
 * Class UnexpectedTypeException.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class UnexpectedTypeException extends \InvalidArgumentException
{
    /**
     * UnexpectedTypeException constructor.
     *
     * @param mixed  $value
     * @param string $expectedType
     */
    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType, is_object($value) ? get_class($value) : gettype($value)));
    }
}
