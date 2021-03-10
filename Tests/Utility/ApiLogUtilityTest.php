<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Utility;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiLog;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType;
use Chaplean\Bundle\ApiClientBundle\Query\ApiLogQuery;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class ApiLogUtilityTest.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ApiLogUtilityTest extends MockeryTestCase
{
    /**
     * @var ApiLogQuery|\Mockery\MockInterface
     */
    private $apiLogQuery;

    /**
     * @var Registry|\Mockery\MockInterface
     */
    private $registry;

    /**
     * @var EntityManagerInterface|\Mockery\MockInterface
     */
    private $em;

    protected function setUp(): void
    {
        $this->apiLogQuery = \Mockery::mock(ApiLogQuery::class);
        $this->em = \Mockery::mock(EntityManagerInterface::class);
        $this->registry = \Mockery::mock(Registry::class);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::logResponse()
     *
     * @return void
     */
    public function testLogRequestInDatabase()
    {
        $methodType = new ApiMethodType();
        $apiMethodRepo = \Mockery::mock(EntityRepository::class);
        $apiMethodRepo->shouldReceive('findOneBy')->once()->with(['keyname' => 'get'])->andReturn($methodType);

        $statusCodeType = new ApiStatusCodeType();
        $apiStatusCodeRepo = \Mockery::mock(EntityRepository::class);
        $apiStatusCodeRepo->shouldReceive('findOneBy')->once()->with(['code' => 200])->andReturn($statusCodeType);

        $em = \Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('getRepository')->once()->with(ApiMethodType::class)->andReturn($apiMethodRepo);

        $em->shouldReceive('getRepository')->once()->with(ApiStatusCodeType::class)->andReturn($apiStatusCodeRepo);

        $em->shouldReceive('persist')->once();
        $em->shouldNotReceive('flush');

        $apiLogQuery = \Mockery::mock(ApiLogQuery::class);
        $registry = \Mockery::mock(Registry::class);
        $registry->shouldReceive('getManager')->once()->andReturn($em);

        $config = [
            'enable_database_logging' => null // true value will be convert to null on configuration normalization
        ];

        $utility = new ApiLogUtility($config, $apiLogQuery, $registry);
        $utility->logResponse(new PlainResponse(new Response(200, [], ''), 'get', 'url', []));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::logResponse()
     *
     * @return void
     */
    public function testLogRequestInDatabaseWithUnknownStatusCode()
    {
        $methodType = new ApiMethodType();
        $apiMethodRepo = \Mockery::mock(EntityRepository::class);
        $apiMethodRepo->shouldReceive('findOneBy')->once()->with(['keyname' => 'get'])->andReturn($methodType);

        $statusCodeType = new ApiStatusCodeType();
        $apiStatusCodeRepo = \Mockery::mock(EntityRepository::class);
        $apiStatusCodeRepo->shouldReceive('findOneBy')->once()->with(['code' => 418])->andReturn($statusCodeType);

        $em = \Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('getRepository')->once()->with(ApiMethodType::class)->andReturn($apiMethodRepo);

        $em->shouldReceive('getRepository')->once()->with(ApiStatusCodeType::class)->andReturn($apiStatusCodeRepo);

        $em->shouldReceive('persist')->once();
        $em->shouldNotReceive('flush');

        $apiLogQuery = \Mockery::mock(ApiLogQuery::class);
        $registry = \Mockery::mock(Registry::class);
        $registry->shouldReceive('getManager')->once()->andReturn($em);

        $config = [
            'enable_database_logging' => null, // true value will be convert to null on configuration normalization
        ];

        $utility = new ApiLogUtility($config, $apiLogQuery, $registry);

        $utility->logResponse(new PlainResponse(new Response(418, [], ''), 'get', 'url', []));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Database logging is enabled, you must register the doctrine service
     *
     * @return void
     */
    public function testConstructFailsIfConfigEnablesLoggingWithoutTheRequiredServices()
    {
        $apiLogQuery = \Mockery::mock(ApiLogQuery::class);

        $config = [
            'enable_database_logging' => null,
        ];

        new ApiLogUtility($config, $apiLogQuery);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::logResponse()
     *
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testMissingServicesAreIgnoredIfLoggingIsDisabled()
    {
        $apiLogQuery = \Mockery::mock(ApiLogQuery::class);

        $config = [
        ];

        new ApiLogUtility($config, $apiLogQuery);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::deleteMostRecentThan()
     *
     * @return void
     */
    public function testDeleteMostRecentThan()
    {
        $date = new \DateTime();
        $em = \Mockery::mock(EntityManagerInterface::class);
        $querySearch = \Mockery::mock(AbstractQuery::class);
        $queryDelete = \Mockery::mock(AbstractQuery::class);
        $apiLogQuery = \Mockery::mock(ApiLogQuery::class);
        $registry = \Mockery::mock(Registry::class);

        $config = [
            'enable_database_logging' => true,
        ];

        $registry->shouldReceive('getManager')->once()->andReturn($em);
        $apiLogQuery->shouldReceive('createFindIdsMostRecentThan')->once()->with($date)->andReturn($querySearch);
        $querySearch->shouldReceive('getResult')->once()->andReturn([['id' => 125], ['id' => 126]]);
        $apiLogQuery->shouldReceive('createDeleteById')->once()->with(125)->andReturn($queryDelete);
        $queryDelete->shouldReceive('execute')->once()->andReturnNull();
        $apiLogQuery->shouldReceive('createDeleteById')->once()->with(126)->andReturn($queryDelete);
        $queryDelete->shouldReceive('execute')->once()->andThrow(new \Exception());

        $utility = new ApiLogUtility($config, $apiLogQuery, $registry);
        $result = $utility->deleteMostRecentThan($date);

        $this->assertEquals(1, $result);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::getLogByUuid()
     *
     * @return void
     */
    public function testGetResponseByUuidWithoutStoredLogs()
    {
        $apiLogQuery = \Mockery::mock(ApiLogQuery::class);
        $registry = \Mockery::mock(Registry::class);
        $em = \Mockery::mock(EntityManagerInterface::class);
        $repository = \Mockery::mock(EntityRepository::class);
        $log = new ApiLog();

        $config = [
            'enable_database_logging' =>  null, // true value will be convert to null on configuration normalization,
        ];

        $registry->shouldReceive('getManager')->once()->andReturn($em);
        $em->shouldReceive('getRepository')->andReturn($repository);
        $repository->shouldReceive('findOneByResponseUuid')->with(42)->andReturn($log);

        $utility = new ApiLogUtility($config, $apiLogQuery, $registry);
        $this->assertEquals($log, $utility->getLogByUuid('42'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::getLogByUuid()
     *
     * @return void
     */
    public function testGetResponseByUuidWithStoredLogs()
    {
        $methodType = new ApiMethodType();
        $apiMethodRepo = \Mockery::mock(EntityRepository::class);
        $apiMethodRepo->shouldReceive('findOneBy')->once()->with(['keyname' => 'get'])->andReturn($methodType);

        $statusCodeType = new ApiStatusCodeType();
        $apiStatusCodeRepo = \Mockery::mock(EntityRepository::class);
        $apiStatusCodeRepo->shouldReceive('findOneBy')->once()->with(['code' => 200])->andReturn($statusCodeType);

        $em = \Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('getRepository')->once()->with(ApiMethodType::class)->andReturn($apiMethodRepo);

        $em->shouldReceive('getRepository')->once()->with(ApiStatusCodeType::class)->andReturn($apiStatusCodeRepo);

        $em->shouldReceive('persist')->once();
        $em->shouldNotReceive('flush');

        $apiLogQuery = \Mockery::mock(ApiLogQuery::class);
        $registry = \Mockery::mock(Registry::class);
        $registry->shouldReceive('getManager')->once()->andReturn($em);

        $config = [
            'enable_database_logging' =>  null, // true value will be convert to null on configuration normalization,
        ];

        $response = new PlainResponse(new Response(200, [], ''), 'get', 'url', []);

        $utility = new ApiLogUtility($config, $apiLogQuery, $registry);
        $utility->logResponse($response);

        $em->shouldNotReceive('getRepository')->with(ApiLog::class);

        $this->assertNotNull($utility->getLogByUuid($response->getUuid()));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledExplicitDefinition()
    {
        $this->registry->shouldReceive('getManager')->once()->andReturn($this->em);

        $utility = new ApiLogUtility(
            [
                'enable_database_logging' => [
                    'type'     => 'inclusive',
                    'elements' => [
                        'bar_api'
                    ]
                ]
            ],
            $this->apiLogQuery,
            $this->registry
        );

        $this->assertTrue($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledTildDefinition()
    {
        $this->registry->shouldReceive('getManager')->once()->andReturn($this->em);

        $utility = new ApiLogUtility(
            [
                'enable_database_logging' => null
            ],
            $this->apiLogQuery,
            $this->registry
        );

        $this->assertTrue($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledExclusiveDefinition()
    {
        $this->registry->shouldReceive('getManager')->once()->andReturn($this->em);

        $utility = new ApiLogUtility(
            [
                'enable_database_logging' => [
                    'type' => 'exclusive',
                    'elements' => [
                        'foo_api'
                    ]
                ]
            ],
            $this->apiLogQuery,
            $this->registry
        );

        $this->assertTrue($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledExplicitDefinition()
    {
        $this->registry->shouldReceive('getManager')->once()->andReturn($this->em);

        $utility = new ApiLogUtility(
            [
                'enable_database_logging' => [
                    'type' => 'exclusive',
                    'elements' => [
                        'bar_api'
                    ]
                ]
            ],
            $this->apiLogQuery,
            $this->registry
        );

        $this->assertFalse($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledByDefault()
    {
        $this->registry->shouldReceive('getManager')->once()->andReturn($this->em);

        $utility = new ApiLogUtility(
            [],
            $this->apiLogQuery,
            $this->registry
        );

        $this->assertFalse($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledNotDefineApiName()
    {
        $this->registry->shouldReceive('getManager')->once()->andReturn($this->em);

        $utility = new ApiLogUtility(
            [
                'enable_database_logging' => [
                    'type' => 'inclusive',
                    'elements' => [
                        'foo_api'
                    ]
                ]
            ],
            $this->apiLogQuery,
            $this->registry
        );

        $this->assertFalse($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility::logResponse
     *
     * @return void
     */
    public function testLogResponseWithDisabledApi()
    {
        $this->registry->shouldReceive('getManager')->once()->andReturn($this->em);
        $this->em->shouldNotReceive('persist');

        $config = [
            'enable_database_logging' => [
                'type'     => 'exclusive',
                'elements' => [
                    'foo_api'
                ]
            ]
        ];

        $utility = new ApiLogUtility($config, $this->apiLogQuery, $this->registry);
        $utility->logResponse(new PlainResponse(new Response(200, [], ''), 'get', 'url', []), 'foo_api');
    }
}
