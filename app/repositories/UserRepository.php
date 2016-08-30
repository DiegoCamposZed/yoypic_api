<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 23/08/2016
 * Time: 13:51
 */
namespace App\repositories;

use App\models\User;

class UserRepository extends BaseRepository implements  BaseRepositoryInterface {
    public function __construct($app){
        parent::__construct($app->firebase);
    }
    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function findOne($parameters)
    {

//        $options = array_merge($parameters, array('limitToFirst'=>1));
        $options = array_merge($parameters, array());
        $json = json_decode($this->firebase->get('users', $options));

        $array = (array) $json;
        if($json != null && !empty($array)){
            foreach($json as $userObj){
                $result = $this->objectToUser($userObj);
            }
            return $result;

        }
        return null;
    }

    public function findAll($parameters = array())
    {

        $json = json_decode($this->firebase->get('users', $parameters));
        $array = (array) $json;
        if($json != null && !empty($array)){
            foreach ($json as $userObj) {
                $users[] = $this->objectToUser($userObj);

            }
            return $users;
        }

        return null;
    }

    public function save($data)
    {

        if(!empty($data) && property_exists($data, "uid")){

            $newUser = $this->objectToUser($data);
            return $this->firebase->set('users/' . $newUser->getUid() , $newUser);
        }
        else
            return false;
    }

    public function remove($uid)
    {
        if(!empty($uid)){

            return $this->firebase->set('users/' . $uid , null);
        }
        else
            return false;

    }

    public function objectToUser($userObj){
        $uid = property_exists($userObj, 'uid') ? $userObj->uid : null;
        $username = property_exists($userObj, 'username') ? $userObj->username : null;
        $msisdn = property_exists($userObj, 'msisdn') ? $userObj->msisdn : null;
        $phonePrefix = property_exists($userObj, 'phonePrefix') ? $userObj->phonePrefix : null;
        $createdAt = property_exists($userObj, 'createdAt') ? $userObj->createdAt : null;
        $lastLoginAt = property_exists($userObj, 'lastLoginAt') ? $userObj->lastLoginAt : null;
        $updatedAt = property_exists($userObj, 'updatedAt') ? $userObj->updatedAt : null;

        return new User($uid, $username, $msisdn, $phonePrefix, $createdAt, $lastLoginAt, $updatedAt);
    }
}