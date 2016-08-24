<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 23/08/2016
 * Time: 15:01
 */
namespace App\repositories;

class BaseRepository {

    public  $firebase;

    public function __construct($firebase){
        $this->firebase = $firebase;
    }
}