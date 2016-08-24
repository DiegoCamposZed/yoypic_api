<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 09/08/2016
 * Time: 10:15
 */

namespace App\controllers;

use App\models\FirebaseNotification;

class Notifications
{
    protected $request;
    protected $response;

    public function sendNotification()
    {


        $body = $this->app->request()->getBody();
        $bodyObj = json_decode($body);

        $msisdns = $bodyObj->destinationIds ;
        $tokens = $this->getCurrentTokensByMsisdn($msisdns);

        if($tokens){
            foreach($tokens as $token){
                $notification = new FirebaseNotification($token->getId(), '', $bodyObj);

                $responseBody = $notification->jsonSerialize();

                try {
                    $response = $this->app->guzzle->post('https://fcm.googleapis.com/fcm/send',
                        [
                            'verify' => false,
                            'headers' => [
                                'Content-Type'  => 'application/json',
                                'Authorization' => 'key=AIzaSyDQOmiaVwz_F13I_eA7pODoguCHOjrElKM',

                            ],
                            'body' => $responseBody
                        ]
                    );
                    echo $response->getBody();
                } catch (RequestException $e) {
                    echo $e->getRequest() . "\n";
                    if ($e->hasResponse()) {
                        echo $e->getResponse() . "\n";
                    }
                }

            }

        }
    }

    public function getCurrentTokensByMsisdn($msisdns)
    {

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
                return $tokens;

        } catch(\Exception $e){
            $this->app->log->error("Yoypic: getCurrentTokensByUserId: " . $e->getMessage());

        }

        return false;


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
