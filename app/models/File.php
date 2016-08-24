<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 02/08/2016
 * Time: 16:04
 */

namespace App\models;

/**
 * Class File.
 */
class File implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $ownerId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var float
     */
    private $createdAt;

    /**
     * @var float
     */
    private $updatedAt;

    /**
     * @var object
     */
    private $acl;

    /**
     * @var object
     */
    private $localPaths;

    /**
     * @var object
     */
    private $interactions;


    /**
     * @param $id
     * @param $message
     * @param $ownerId
     * @param $title
     * @param $url
     * @param $createdAt
     * @param $updatedAt
     * @param $acl
     * @param $localPaths
     * @param $interactions
     */
    public function __construct($id, $message, $ownerId, $title, $url, $createdAt, $updatedAt, $acl, $localPaths, $interactions)
    {
        $this->id = $id;
        $this->message = $message;
        $this->ownerId = $ownerId;
        $this->title = $title;
        $this->url = $url;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->acl = $acl;
        $this->localPaths = $localPaths;
        $this->interactions = $interactions;

    }

    /**
     * @return array
     */
    /*
     *       'acl' => $this->acl,
     *       'localPaths' => $this->localPaths,
     */
    public function jsonSerialize()
    {
        return [
            'type' => 'file',
            'id' => $this->id,
            'message' => $this->message,
            'ownerId' => $this->ownerId,
            'title' => $this->title,
            'url' => $this->url,
            'acl' => $this->acl,
            'interactions' =>$this->interactions,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt

        ];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return object
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param object $acl
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
    }

    /**
     * @return object
     */
    public function getLocalPaths()
    {
        return $this->localPaths;
    }

    /**
     * @param object $localPaths
     */
    public function setLocalPaths($localPaths)
    {
        $this->localPaths = $localPaths;
    }

    /**
     * @return object
     */
    public function getInteractions()
    {
        return $this->interactions;
    }

    /**
     * @param object $interactions
     */
    public function setInteractions($interactions)
    {
        $this->interactions = $interactions;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return float
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param float $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return float
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param float $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}