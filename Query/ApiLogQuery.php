<?php

namespace Chaplean\Bundle\ApiClientBundle\Query;

use Chaplean\Bundle\ApiClientBundle\Entity\ApiLog;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ApiLogQuery.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Query
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class ApiLogQuery
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ApiLogQuery constructor.
     *
     * @param RegistryInterface|ManagerRegistry $registry
     */
    public function __construct(/** ManagerRegistry */$registry)
    {
        $this->em = $registry->getManager();
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return Query
     */
    public function createFindIdsMostRecentThan(\DateTime $dateTime)
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('log.id')
            ->from(ApiLog::class, 'log')
            ->where('log.dateAdd < :dateLimit')
            ->setParameter('dateLimit', $dateTime->format('Y-m-d'))
            ->getQuery();
    }

    /**
     * @param integer $id
     *
     * @return Query|null
     */
    public function createDeleteById($id)
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->delete(ApiLog::class, 'log')
            ->where('log.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
    }
}
