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
                'notification' => get_object_vars($this->notification)
            ];
        else
            return [
                'to' => $this->to,
                'data' => $this->data->content
            ];

    }
}
