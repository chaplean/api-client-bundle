<?php

namespace Chaplean\Bundle\ApiClientBundle\DataFixtures\Entity;

use Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadApiMethodTypeData.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\DataFixtures\Entity
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class LoadApiMethodTypeData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $method = new ApiMethodType();
        $method->setKeyname('get');
        $manager->persist($method);
        $this->setReference('api-method-type-get', $method);

        $method = new ApiMethodType();
        $method->setKeyname('post');
        $manager->persist($method);
        $this->setReference('api-method-type-post', $method);

        $method = new ApiMethodType();
        $method->setKeyname('put');
        $manager->persist($method);
        $this->setReference('api-method-type-put', $method);

        $method = new ApiMethodType();
        $method->setKeyname('patch');
        $manager->persist($method);
        $this->setReference('api-method-type-patch', $method);

        $method = new ApiMethodType();
        $method->setKeyname('delete');
        $manager->persist($method);
        $this->setReference('api-method-type-delete', $method);

        $manager->flush();
    }
}
