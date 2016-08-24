<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 23/08/2016
 * Time: 17:20
 */

namespace App\repositories;

interface BaseRepositoryInterface
{
    public function find($id);
    public function findOne($parameters);
    public function findAll($parameters);
    public function save($object);
    public function remove($object);
}