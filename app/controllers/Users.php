<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 08/08/2016
 * Time: 9:38
 */

namespace App\controllers;

class Users
{
    protected $app;
    protected $request;
    protected $response;

    public function index()
    {
        echo "This is the home page";
    }

    public function getSubscribedContacts($contacts)
    {

        try{
            $users = $this->app->userRepository->findAll(array());

            foreach($users as $user){
                if(array_search($user->getMsisdn(),$contacts) !== false || array_search($user->getPhonePrefix() . $user->getMsisdn(),$contacts) !== false)
                    $result[] = $user;
            }

            $payload = new \stdClass();
            $payload->data = $result;
        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getSubscribedContacts: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function mySubscribedContacts()
    {
        $result = array();

        $payload = new \stdClass();

        try{
            $body = $this->app->request()->getBody();
            $contacts = json_decode($body);

            $users = $this->app->userRepository->findAll(array());


            foreach($users as $user){
                if(array_search($user->getMsisdn(),$contacts) !== false || array_search($user->getPhonePrefix() . $user->getMsisdn(),$contacts) !== false)
                    $result[] = $user;
            }

            $payload->data = $result;
        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " mySubscribedContacts: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function getUserByMsisdn($msisdns){
        $payload = new \stdClass();

        $result = array();

        try{
            foreach($msisdns as $msisdn){
                $phoneNumber = $this->parseMsisdn($msisdn);

                if($phoneNumber != null) {

                    $parameters = array('orderBy' => '"msisdn"', 'equalTo' => '"' . $phoneNumber->getNationalNumber() . '"');
                    $user = $this->app->userRepository->findOne($parameters);
                    if($user != null)
                        $result[] = $user;

                }
            }

            $payload->data = $result;

        } catch(\Exception $e){
            $this->app->log->error("Yoypic: getUserByMsisdn: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function parseMsisdn($msisdn){
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($msisdn, "ES");
            return $numberProto;
        } catch (\libphonenumber\NumberParseException $e) {
            $this->app->log->error("Yoypic: parseMsisdn: " . $e->getMessage());

        }
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
