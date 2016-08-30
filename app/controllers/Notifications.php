<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 09/08/2016
 * Time: 10:15
 */

namespace App\controllers;

use App\models\Error;
use App\models\FirebaseNotification;

class Notifications
{
    protected $request;
    protected $response;

    public function sendNotification()
    {

        $payload = new \stdClass();

        $body = $this->app->request()->getBody();

        $bodyObj = json_decode($body);

        // Get notification Field
        $notification = '';
        if(property_exists($bodyObj, 'notification'))
            $notification = $bodyObj->notification;

        // Get data Field
        $data = '';
        if(property_exists($bodyObj, 'data'))
            $data = $bodyObj->data;

        if(property_exists($bodyObj, 'to')) {
            $msisdns = $bodyObj->to;
        } else if(property_exists($bodyObj, 'destinationIds')){
            $msisdns = $bodyObj->destinationIds;
        }

        if($msisdns){
            $tokens = $this->getCurrentTokensByMsisdn($msisdns);
            if($tokens){
                foreach($tokens as $token){
                    $notificationObj = new FirebaseNotification($token->getId(), $notification, $data);

                    $responseBody = $notificationObj->jsonSerialize();

                    try {
                        $request = $this->app->guzzle->post('https://fcm.googleapis.com/fcm/send',
                            [
                                'Content-Type'  => 'application/json',
                                'Authorization' => 'key=AIzaSyDQOmiaVwz_F13I_eA7pODoguCHOjrElKM',
                            ],
                            $responseBody,
                            []
                        );

                        $response = $request->send();

                        $payload->data[] = " Send Notification: " . $token->getUid() . " RESULT: " .$response->getBody();
                        $this->app->log->info(APP_NAME . " Send Notification: TOKEN: " . $token->getId() . " USER: " . $token->getUid() . " RESULT: " .$response->getBody());


                    } catch (RequestException $e) {

  //                      echo $e->getRequest() . "\n";
                        $this->app->log->error(APP_NAME . " Send Notification ERROR REQUEST : " .$e->getRequest());
                        $payload->error[] = new Error($e->getCode(), " Send Notification ERROR REQUEST : " .$e->getRequest(), '');

                        if ($e->hasResponse()) {
//                            echo $e->getResponse() . "\n";
                            $this->app->log->error(APP_NAME . " Send Notification ERROR RESPONSE : " .$e->getResponse());
                            $payload->error[] = new Error($e->getCode(), " Send Notification ERROR RESPONSE : " .$e->getResponse(), '');

                        }
                    }

                }

            } else {
                $this->app->log->error(APP_NAME . " Send Notification: Tokens not found for: " . implode(',', $msisdns));
                $payload->error = new Error(404, " Send Notification: Tokens not found for: " . implode(',', $msisdns), '');

            }

            $this->response->headers->set('Content-Type', 'application/json');
            echo json_encode($payload);

        }

    }

    public function getCurrentTokensByMsisdn($msisdns)
    {

        $tokens = array();

        try{
            if(is_string($msisdns))
                $msisdns = array($msisdns);

            $users = $this->app->userRepository->findAll(array());
            foreach($users as $user){
                if(array_search($user->getMsisdn(),$msisdns) !== false || array_search($user->getPhonePrefix() . $user->getMsisdn(),$msisdns) !== false) {
                  $parameters = array('orderBy' => '"uid"', 'equalTo' => '"' .$user->getUid() . '"');
                  $userTokens = $this->app->tokenRepository->findAll($parameters);
                  $tokens = array_merge($tokens, $userTokens);
                }
            }

            if(!empty($tokens))
                return $tokens;

        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getCurrentTokensByUserId: " . $e->getMessage());

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
