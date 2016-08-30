<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 10/08/2016
 * Time: 10:13
 */


namespace App\controllers;

use App\models\Token;


class Tokens
{
    protected $app;
    protected $request;
    protected $response;

    public function index()
    {
        echo "This is the home page";
    }

    public function getCurrentTokens()
    {
        $payload = new \stdClass();

        try{

            $parameters = array();
            $tokens = $this->app->tokenRepository->findAll($parameters);

            $payload->data = $tokens;
        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getCurrentTokens: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function getCurrentTokensByUserId($uids)
    {
        $payload = new \stdClass();

        $tokens = array();

        try{
            if(is_string($uids))
                $uids = array($uids);

            foreach($uids as $uid){
                $json = json_decode($this->app->firebase->get('tokens', array('orderBy' => '"uid"', 'equalTo' => '"' .$uid . '"')));

                if ($json != null && is_array($json)) {
                    foreach ($json as $key => $tokenData) {
                        $platform = isset($tokenData->platform)? $tokenData->platform : 'undefined';

                        $tokens[] = new Token($key, $tokenData->uid, $platform, $tokenData->updatedAt);
                    }
                    $payload->data = $tokens;
                } else {
                    $payload = $json;
                }

            }
            if(!empty($token))
                $payload->data = $tokens;

        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getCurrentTokensByUserId: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function getCurrentTokensByMsisdn($msisdns)
    {
        $payload = new \stdClass();

        $tokens = array();

        try{
            if(is_string($msisdns))
                $msisdns = array($msisdns);

            foreach($msisdns as $msisdn){
                $parameters = array('orderBy' => '"msisdn"', 'equalTo' => '"' .$msisdn . '"');
                $users = $this->app->userRepository->findAll($parameters);
                foreach($users as $user){
                    $parameters = array('orderBy' => '"uid"', 'equalTo' => '"' .$user->getUid() . '"');

                    $userTokens = $this->app->tokenRepository->findAll($parameters);

                    $tokens = array_merge($tokens, $userTokens);

                }
            }

            if(!empty($tokens))
                $payload->data = $tokens;

        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getCurrentTokensByUserId: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


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

    // Init
    public function init()
    {
        // do things now that app, request and response are set.

    }

}
