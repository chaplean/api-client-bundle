<?php

namespace Chaplean\Bundle\ApiClientBundle\Utility;

use Chaplean\Bundle\ApiClientBundle\Api\ResponseInterface;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiLog;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType;
use Chaplean\Bundle\ApiClientBundle\Query\ApiLogQuery;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

/**
 * Class ApiLogUtility.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ApiLogUtility
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var ApiLogQuery
     */
    protected $apiLogQuery;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array|ApiLog[]
     */
    protected $logs;

    /**
     * ApiLogUtility constructor.
     *
     * @param array       $parameters
     * @param ApiLogQuery $apiLogQuery
     * @param Registry    $registry
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $parameters, ApiLogQuery $apiLogQuery, Registry $registry = null)
    {
        $this->apiLogQuery = $apiLogQuery;
        $this->parameters = $parameters;

        if ($registry === null) {
            if (array_key_exists('enable_database_logging', $this->parameters)) {
                throw new \InvalidArgumentException('Database logging is enabled, you must register the doctrine service');
            }
        } else {
            $this->em = $registry->getManager();
        }

        $this->logs = [];
    }

    /**
     * Persists in database a log entity representing the given $response.
     *
     * @param ResponseInterface $response
     * @param string|null       $apiName
     *
     * @return void
     */
    public function logResponse(ResponseInterface $response, string $apiName = null)
    {
        if (!$this->isEnabledLoggingFor($apiName ?: '')) {
            return;
        }

        $methodName = $response->getMethod();
        $codeNumber = $response->getCode();

        /** @var ApiMethodType|null $method */
        $method = $this->em->getRepository(ApiMethodType::class)->findOneBy(['keyname' => strtolower($methodName)]);
        /** @var ApiStatusCodeType|null $statusCode */
        $statusCode = $this->em->getRepository(ApiStatusCodeType::class)->findOneBy(['code' => $codeNumber]);

        $apiLog = new ApiLog();
        $apiLog->setUrl($response->getUrl());
        $apiLog->setDataSended($response->getData());
        $apiLog->setDataReceived($response->getContent());
        $apiLog->setDateAdd(new \DateTime());
        $apiLog->setResponseUuid($response->getUuid());

        if ($method) {
            $apiLog->setMethod($method);
        }

        if ($statusCode) {
            $apiLog->setStatusCode($statusCode);
        }

        $this->logs[$response->getUuid()] = $apiLog;
        $this->em->persist($apiLog);
    }

    /**
     * Persists in database a log entity representing the given $response.
     *
     * @param \DateTime $dateTime
     *
     * @return integer
     */
    public function deleteMostRecentThan(\DateTime $dateTime)
    {
        $apiLogDeleted = 0;

        $query = $this->apiLogQuery->createFindIdsMostRecentThan($dateTime);
        $idsToRemove = array_map(function($data) {
            return $data['id'];
        }, $query->getResult());

        foreach ($idsToRemove as $id) {
            try {
                $queryRemove = $this->apiLogQuery->createDeleteById($id);
                $queryRemove->execute();
                $apiLogDeleted ++;
            } catch (\Exception $e){};
        }

        return $apiLogDeleted;
    }

    /**
     * Returns the ApiLog entity corresponding to the given $uuid. First search in memory for not yet flushed entity, then searches in the database.
     *
     * @param string $uuid
     *
     * @return ApiLog|null
     */
    public function getLogByUuid($uuid)
    {
        return $this->logs[$uuid] ?? $this->em->getRepository(ApiLog::class)->findOneByResponseUuid($uuid);
    }

    /**
     * Warning: Will be move in v2.X
     *
     * Check if database logging for $apiName is enabled
     *
     * @param string $apiName
     *
     * @return boolean
     */
    public function isEnabledLoggingFor(string $apiName): bool
    {
        if (!array_key_exists('enable_database_logging', $this->parameters)) {
            return false;
        }

        if ($this->parameters['enable_database_logging'] === null) {
            return true;
        }

        $isEnabled = in_array($apiName, $this->parameters['enable_database_logging']['elements'], true);

        if ($this->parameters['enable_database_logging']['type'] === 'exclusive') {
            return !$isEnabled;
        }

        return $isEnabled;
    }
}
