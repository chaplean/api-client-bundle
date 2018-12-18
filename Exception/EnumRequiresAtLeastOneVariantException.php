<?php

namespace Chaplean\Bundle\ApiClientBundle\Exception;

/**
 * Class EnumRequiresAtLeastOneVariantException.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class EnumRequiresAtLeastOneVariantException extends \InvalidArgumentException
{
    /**
     * EnumRequiresAtLeastTwoVariantsException constructor.
     */
    public function __construct()
    {
        parent::__construct('Enumeration type require at least one variant');
    }
}
