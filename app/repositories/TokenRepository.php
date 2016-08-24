<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 23/08/2016
 * Time: 17:19
 */

namespace App\repositories;

use App\models\Token;

class TokenRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct($app){
        parent::__construct($app->firebase);
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function findOne($parameters)
    {
        // TODO: Implement findOne() method.
    }

    public function findAll($parameters)
    {


        $json = json_decode($this->firebase->get('tokens', $parameters));

        $tokens = array();
        if ($json != null) {
            foreach ($json as $key => $tokenData) {
                $platform = isset($tokenData->platform)? $tokenData->platform : 'undefined';
                $tokens[] = new Token($key, $tokenData->uid, $platform, $tokenData->updatedAt);
            }
        }

        return $tokens;


    }

    public function save($object)
    {
        // TODO: Implement save() method.
    }

    public function remove($object)
    {
        // TODO: Implement remove() method.
    }
}