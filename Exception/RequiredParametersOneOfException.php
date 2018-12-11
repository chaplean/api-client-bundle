<?php

namespace Chaplean\Bundle\ApiClientBundle\Exception;

/**
 * Class RequiredParametersOneOfException.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequiredParametersOneOfException extends \InvalidArgumentException
{
    /**
     * RequiredParametersOneOfException constructor.
     *
     */
    public function __construct()
    {
        parent::__construct('One of required parameters not in original Object');
    }
}
