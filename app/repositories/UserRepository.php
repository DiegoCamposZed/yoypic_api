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
        $result = null;

        $options = array_merge($parameters, array('limitToFirst'=>1));
        $json = json_decode($this->firebase->get('users', $options));
        if($json != null){
            foreach($json as $user){
                $result = new User($user->uid, $user->username, $user->msisdn, $user->phonePrefix, $user->createdAt, $user->lastLoginAt, $user->updatedAt);
            }
        }
        return $result;
    }

    public function findAll($parameters = array())
    {
        $json = json_decode($this->firebase->get('users', $parameters));

        $users = array();
        if ($json != null) {
            foreach ($json as $user) {
                $users[] = new User($user->uid, $user->username, $user->msisdn, $user->phonePrefix, $user->createdAt, $user->lastLoginAt, $user->updatedAt);
            }
        }
        return $users;

    }

    public function save($user)
    {
        // TODO: Implement save() method.
    }

    public function remove($user)
    {
        // TODO: Implement remove() method.
    }
}