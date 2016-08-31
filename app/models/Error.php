<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 30/08/2016
 * Time: 12:35
 */

namespace App\models;

/**
 * Class Error.
 */
class Error implements \JsonSerializable
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $fields;

    public function __construct($code, $message, $fields)
    {
        $this->code = $code;
        $this->message = $message;
        $this->fields = $fields;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }



    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            'code' => $this->code,
            'message' => $this->message,
            'fields' => $this->fields
        ];

    }
}