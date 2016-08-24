<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 05/08/2016
 * Time: 14:50
 */

namespace App\controllers;

class Home
{
    protected $request;
    protected $response;

    public function index()
    {
        echo "This is the home page";
    }

    public function hello($name)
    {
        echo "Hello, $name";
    }

    public function setApp($app)
    {
        $this->app = $app;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }
}
