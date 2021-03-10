<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Query;

use Chaplean\Bundle\ApiClientBundle\Entity\ApiLog;;
use Chaplean\Bundle\ApiClientBundle\Query\ApiLogQuery;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ApiLogQueryTest.
 *
 * @namespace Tests\Chaplean\Bundle\ApiClientBundle\Query
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ApiLogQueryTest extends MockeryTestCase
{
    /**
     * @var RegistryInterface|\Mockery\MockInterface
     */
    private $doctrine;

    /**
     * @var EntityManager|\Mockery\MockInterface
     */
    private $manager;

    /**
     * @var ApiLogQuery
     */
    private $apiLogQuery;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->manager = \Mockery::mock(EntityManager::class);
        $this->doctrine = \Mockery::mock(RegistryInterface::class);
        $this->doctrine->shouldReceive('getManager')->once()->andReturn($this->manager);

        $this->apiLogQuery = new ApiLogQuery($this->doctrine);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Query\ApiLogQuery::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ApiLogQuery::class, $this->apiLogQuery);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Query\ApiLogQuery::createFindIdsMostRecentThan()
     *
     * @return void
     */
    public function testCreateFindIdsMostRecentThan()
    {
        $date = \Mockery::mock(\DateTime::class);
        $query = \Mockery::mock(AbstractQuery::class);
        $queryBuilder = \Mockery::mock(QueryBuilder::class);

        $this->manager->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('select')->once()->with('log.id')->andReturnSelf();
        $queryBuilder->shouldReceive('from')->once()->withArgs([ApiLog::class, 'log'])->andReturnSelf();
        $queryBuilder->shouldReceive('where')->once()->with('log.dateAdd < :dateLimit')->andReturnSelf();
        $date->shouldReceive('format')->once()->with('Y-m-d')->andReturn('2000-01-01');
        $queryBuilder->shouldReceive('setParameter')->once()->withArgs(['dateLimit', '2000-01-01'])->andReturnSelf();
        $queryBuilder->shouldReceive('getQuery')->once()->andReturn($query);

        $queryResult = $this->apiLogQuery->createFindIdsMostRecentThan($date);

        $this->assertEquals($queryResult, $query);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Query\ApiLogQuery::createDeleteById()
     *
     * @return void
     */
    public function testCreateDeleteById()
    {
        $query = \Mockery::mock(AbstractQuery::class);
        $queryBuilder = \Mockery::mock(QueryBuilder::class);

        $this->manager->shouldReceive('createQueryBuilder')->once()->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('delete')->once()->withArgs([ApiLog::class, 'log'])->andReturnSelf();
        $queryBuilder->shouldReceive('where')->once()->with('log.id = :id')->andReturnSelf();
        $queryBuilder->shouldReceive('setParameter')->once()->withArgs(['id', 125])->andReturnSelf();
        $queryBuilder->shouldReceive('getQuery')->once()->andReturn($query);

        $queryResult = $this->apiLogQuery->createDeleteById(125);

        $this->assertEquals($queryResult, $query);
    }
}
