<?php

namespace Chaplean\Bundle\ApiClientBundle\DataFixtures\Entity;

use Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadApiStatusCodeTypeData.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\DataFixtures\Entity
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class LoadApiStatusCodeTypeData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $method = new ApiStatusCodeType();
        $method->setCode(0);
        $method->setKeyname('request_failed_to_run');
        $manager->persist($method);
        $this->setReference('api-status-code-type-0', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(200);
        $method->setKeyname('ok');
        $manager->persist($method);
        $this->setReference('api-status-code-type-200', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(201);
        $method->setKeyname('created');
        $manager->persist($method);
        $this->setReference('api-status-code-type-201', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(202);
        $method->setKeyname('accepted');
        $manager->persist($method);
        $this->setReference('api-status-code-type-202', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(204);
        $method->setKeyname('no_content');
        $manager->persist($method);
        $this->setReference('api-status-code-type-204', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(301);
        $method->setKeyname('moved_permanently');
        $manager->persist($method);
        $this->setReference('api-status-code-type-301', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(302);
        $method->setKeyname('found');
        $manager->persist($method);
        $this->setReference('api-status-code-type-302', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(303);
        $method->setKeyname('see_other');
        $manager->persist($method);
        $this->setReference('api-status-code-type-303', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(304);
        $method->setKeyname('not_modified');
        $manager->persist($method);
        $this->setReference('api-status-code-type-304', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(307);
        $method->setKeyname('temporary_redirect');
        $manager->persist($method);
        $this->setReference('api-status-code-type-307', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(400);
        $method->setKeyname('bad_request');
        $manager->persist($method);
        $this->setReference('api-status-code-type-400', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(401);
        $method->setKeyname('unauthorized');
        $manager->persist($method);
        $this->setReference('api-status-code-type-401', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(403);
        $method->setKeyname('forbidden');
        $manager->persist($method);
        $this->setReference('api-status-code-type-403', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(404);
        $method->setKeyname('not_found');
        $manager->persist($method);
        $this->setReference('api-status-code-type-404', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(405);
        $method->setKeyname('method_not_allowed');
        $manager->persist($method);
        $this->setReference('api-status-code-type-405', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(406);
        $method->setKeyname('not_acceptable');
        $manager->persist($method);
        $this->setReference('api-status-code-type-406', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(412);
        $method->setKeyname('precondition_failed');
        $manager->persist($method);
        $this->setReference('api-status-code-type-412', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(415);
        $method->setKeyname('unsuppported_media_type');
        $manager->persist($method);
        $this->setReference('api-status-code-type-415', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(500);
        $method->setKeyname('internal_server_error');
        $manager->persist($method);
        $this->setReference('api-status-code-type-500', $method);

        $method = new ApiStatusCodeType();
        $method->setCode(501);
        $method->setKeyname('not_implemented');
        $manager->persist($method);
        $this->setReference('api-status-code-type-501', $method);

        $manager->flush();
    }
}
