<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 08/08/2016
 * Time: 12:18
 */

namespace App\models;

/**
 * Class User.
 */
class User implements \JsonSerializable
{
    /**
     * @var string
     */
    private $uid;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $msisdn;

    /**
     * @var string
     */
    private $phonePrefix;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var int
     */
    private $lastLoginAt;

    /**
     * @var int
     */
    private $updatedAt;


    /**
     * @param $uid
     * @param $username
     * @param $msisdn
     * @param $phonePrefix
     * @param $createdAt
     * @param $lastLoginAt
     * @param $updatedAt
     */
    public function __construct($uid, $username, $msisdn, $phonePrefix, $createdAt, $lastLoginAt, $updatedAt)
    {
        $this->uid = $uid;
        $this->username = $username;
        $this->msisdn = $msisdn;
        $this->phonePrefix = $phonePrefix;
        $this->createdAt = $createdAt;
        $this->lastLoginAt = $lastLoginAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * @return string
     */
    public function getPhonePrefix()
    {
        return $this->phonePrefix;
    }

    /**
     * @return float
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return float
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => 'user',
            'uid' => $this->uid,
            'username' => $this->username,
            'msisdn' => $this->msisdn,
            'phonePrefix' => $this->phonePrefix,
            'createdAt' => $this->createdAt,
            'lastLoginAt' => $this->lastLoginAt,
            'updatedAt' => $this->updatedAt
        ];
    }
}
