<?php

namespace Chaplean\Bundle\ApiClientBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_api_method_type")
 */
class ApiMethodType
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
     * @ORM\Column(type="string", unique=true, length=50, nullable=false, name="keyname")
     */
    private $keyname;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chaplean\Bundle\ApiClientBundle\Entity\ApiLog", mappedBy="method")
     */
    private $logs;

    /**
     * ApiStatusCodeType constructor.
     */
    public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

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
     * Get keyname.
     *
     * @return string
     */
    public function getKeyname()
    {
        return $this->keyname;
    }

    /**
     * Set keyname.
     *
     * @param string $keyname
     *
     * @return ApiMethodType
     */
    public function setKeyname($keyname)
    {
        $this->keyname = $keyname;

        return $this;
    }

    /**
     * Get logs.
     *
     * @return ArrayCollection
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Adds a log
     *
     * @param ApiLog $log
     *
     * @return ApiMethodType
     */
    public function addLog(ApiLog $log)
    {
        $this->logs->add($log);

        return $this;
    }

    /**
     * Removes a log
     *
     * @param ApiLog $log
     *
     * @return ApiMethodType
     */
    public function removeLog(ApiLog $log)
    {
        $this->logs->removeElement($log);

        return $this;
    }
}
