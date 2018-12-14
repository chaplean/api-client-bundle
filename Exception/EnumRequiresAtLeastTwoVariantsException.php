<?php

namespace Chaplean\Bundle\ApiClientBundle\Exception;

/**
 * Class EnumRequiresAtLeastTwoVariantsException.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class EnumRequiresAtLeastTwoVariantsException extends \InvalidArgumentException
{
    /**
     * EnumRequiresAtLeastTwoVariantsException constructor.
     */
    public function __construct()
    {
        parent::__construct('Enumeration type require at least two variants');
    }
}
