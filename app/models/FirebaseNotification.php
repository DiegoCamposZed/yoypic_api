<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 09/08/2016
 * Time: 10:52
 */
namespace App\models;

/**
 * Class FirebaseNotification.
 */
class FirebaseNotification implements \JsonSerializable
{
    private $to;
    private $notification;
    private $data;
    private $content_available;
    private $priority;

    /**
     * @param $to
     * @param $notification
     * @param $data
     */
    public function __construct($to, $notification, $data)
    {
        $this->to = $to;
        $this->notification = $notification;
        $this->data = $data;
        $this->content_available = false;
        if(!empty($data))
            $this->content_available = true;
        $this->priority = 'high';

    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }



    /**
     * @return array
     */
    public function jsonSerialize()
    {

        if(!empty($this->notification))
            return [
                'to' => $this->to,
                'data' => get_object_vars($this->data),
                'notification' => get_object_vars($this->notification),
                'content_available' => $this->content_available,
                'priority' => $this->priority
            ];
        else
            return [
                'to' => $this->to,
                'data' => $this->data->content,
                'content_available' => $this->content_available,
                'priority' => $this->priority
            ];

    }
}
