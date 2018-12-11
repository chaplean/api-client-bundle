<?php

namespace Chaplean\Bundle\ApiClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *    name="cl_api_log",
 *    indexes={
 *      @ORM\Index(name="api_log_url_INDEX", columns={"url"}),
 *      @ORM\Index(name="api_log_response_uuid_INDEX", columns={"response_uuid"})
 *    }
 * )
 */
class ApiLog
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, name="url")
     */
    private $url;

    /**
     * @var array|string
     *
     * @ORM\Column(type="json_array", nullable=false, name="data_sended")
     */
    private $dataSended;

    /**
     * @var array|string
     *
     * @ORM\Column(type="json_array", nullable=true, name="date_received")
     */
    private $dataReceived;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false, name="date_add")
     */
    private $dateAdd;

    /**
     * @var ApiMethodType
     *
     * @ORM\ManyToOne(targetEntity="Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType", inversedBy="logs")
     * @ORM\JoinColumn(name="api_method_type_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    private $method;

    /**
     * @var ApiStatusCodeType
     *
     * @ORM\ManyToOne(targetEntity="Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType", inversedBy="logs")
     * @ORM\JoinColumn(name="api_status_code_type_id", referencedColumnName="id", onDelete="RESTRICT")
     */
    private $statusCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, name="response_uuid")
     */
    private $responseUuid;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return ApiLog
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get dataSended.
     *
     * @return array|string
     */
    public function getDataSended()
    {
        return $this->dataSended;
    }

    /**
     * Set dataSended.
     *
     * @param array|string $dataSended
     *
     * @return ApiLog
     */
    public function setDataSended($dataSended)
    {
        $this->dataSended = $dataSended;

        return $this;
    }

    /**
     * Get dataReceived.
     *
     * @return array|string
     */
    public function getDataReceived()
    {
        return $this->dataReceived;
    }

    /**
     * Set dataReceived.
     *
     * @param array|string $dataReceived
     *
     * @return ApiLog
     */
    public function setDataReceived($dataReceived)
    {
        $this->dataReceived = $dataReceived;

        return $this;
    }

    /**
     * Get dateAdd.
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set dateAdd.
     *
     * @param \DateTime $dateAdd
     *
     * @return ApiLog
     */
    public function setDateAdd(\DateTime $dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get method.
     *
     * @return ApiMethodType
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set method.
     *
     * @param ApiMethodType $method
     *
     * @return ApiLog
     */
    public function setMethod(ApiMethodType $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get statusCode.
     *
     * @return ApiStatusCodeType
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set statusCode.
     *
     * @param ApiStatusCodeType $statusCode
     *
     * @return ApiLog
     */
    public function setStatusCode(ApiStatusCodeType $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getResponseUuid()
    {
        return $this->responseUuid;
    }

    /**
     * @param string $responseUuid
     *
     * @return self
     */
    public function setResponseUuid($responseUuid)
    {
        $this->responseUuid = $responseUuid;

        return $this;
    }
}
