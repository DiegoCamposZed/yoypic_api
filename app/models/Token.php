<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 10/08/2016
 * Time: 10:15
 */
namespace App\models;

/**
 * Class Token.
 */
class Token implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $uid;

    /**
     * @var int
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $platform;

    /**
     * @param $id
     * @param $uid
     * @param $updatedAt
     */
    public function __construct($id, $uid, $platform, $updatedAt)
    {
        $this->id = $id;
        $this->uid = $uid;
        $this->platform = $platform;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => 'token',
            'id' => $this->id,
            'uid' => $this->uid,
            'platform' => $this->platform,
            'updatedAt' => $this->updatedAt
        ];
    }
}
