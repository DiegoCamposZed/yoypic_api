<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 08/08/2016
 * Time: 9:38
 */

namespace App\controllers;

use App\models\Error;

class Users
{
    protected $app;
    protected $request;
    protected $response;

    public function index()
    {
        echo "This is the home page";
    }

    public function add(){

        $payload = new \stdClass();

        try{
            $body = $this->app->request()->getBody();
            $userData = json_decode($body);

            // TODO: Check if user already exists
            //$users = $this->app->userRepository->findOne(array());


            if(!empty($userData)){
                $payload->data = $this->app->userRepository->save($userData);
                if($payload->data)
                    $this->response->setStatus(201);
                $this->app->log->info(APP_NAME . " User Added: ");
            } else {
                $this->app->log->error(APP_NAME . " User Not Added: Empty data ");
                $payload = new Error(400, 'Bad Request - User data is Empty', '');
            }



        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " Add User: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);

    }

    public function delete($uid){

        $payload = null;

        try{
            if(!empty($uid)){
                $this->app->userRepository->remove($uid);
                $this->response->setStatus(204);
                $this->app->log->info(APP_NAME . " User Deleted: " . $uid);
            } else {
                $this->app->log->error(APP_NAME . " User Not Deleted: Empty data ");
                $payload = new \stdClass();
                $payload->error = new Error(400, 'Bad Request - User id is Empty', 'uid');

            }

        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " Delete User: " . $uid . " --> " . $e->getMessage());
            $payload = new \stdClass();
            $payload->error = new Error($e->getCode(), $e->getMessage(), '');
        }

        $this->response->headers->set('Content-Type', 'application/json');
        if($payload != null)
            echo json_encode($payload);

    }

    public function getSubscribedContacts($contacts)
    {
        $result = array();

        $payload = new \stdClass();

        try{
            $users = $this->app->userRepository->findAll(array());

            if($users != null) {

                foreach ($users as $user) {
                    if (array_search($user->getMsisdn(), $contacts) !== false || array_search($user->getPhonePrefix() . $user->getMsisdn(), $contacts) !== false)
                        $result[] = $user;
                }
            } else {
                $payload->error = new Error(404, 'Data not available', '');

            }
            $payload->data = $result;

        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getSubscribedContacts: " . $e->getMessage());
            $payload->error = new Error($e->getCode(), $e->getMessage(), '');
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
            $contactList = json_decode($body);
            $contacts = array();
            foreach($contactList->contacts as $contact){

                $contacts[] = trim($contact->phonePrefix) . ($contact->phoneNumber);
            }

            $users = $this->app->userRepository->findAll(array());


            foreach($users as $user){
                if(array_search($user->getPhonePrefix() . $user->getMsisdn(),$contacts) !== false)
                    $result[] = $user;
/*
                if(array_search($user->getMsisdn(),$contacts) !== false || array_search($user->getPhonePrefix() . $user->getMsisdn(),$contacts) !== false)
                    $result[] = $user;
*/
            }

            $payload->data = $result;
        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " mySubscribedContacts: " . $e->getMessage());
            $payload->error = new Error($e->getCode(), $e->getMessage(), '');

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function getUserByUid($uid){
        $payload = new \stdClass();

        $result = array();

        try{

            $parameters = array('orderBy' => '"uid"', 'equalTo' => '"' . $uid . '"');
            $user = $this->app->userRepository->findOne($parameters);
            if($user != null){
                $result[] = $user;
            } else {
                $payload->error = new Error(404, 'User Not Found', 'uid');
            }
            $payload->data = $result;

        } catch(\Exception $e){
            $this->app->log->error("Yoypic: getUserByUid: " . $e->getMessage());
            $payload->error = new Error($e->getCode(), $e->getMessage(), '');

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function getUserByMsisdn($msisdns){
        $payload = new \stdClass();

        $result = array();
        $error = array();

        if(is_string($msisdns))
            $msisdns = array($msisdns);
        try{
            foreach($msisdns as $msisdn){
                $phoneNumber = $this->parseMsisdn($msisdn);

                if($phoneNumber != null) {

                    $parameters = array('orderBy' => '"msisdn"', 'equalTo' => '"' . $phoneNumber->getNationalNumber() . '"');
                    $user = $this->app->userRepository->findOne($parameters);
                    if ($user != null){
                        $result[] = $user;
                    }else{
                        $error[] = $phoneNumber;
                    }

                } else {
                    $this->app->log->error("Yoypic: getUserByMsisdn: invalid phone number" . $phoneNumber);
                    $payload->error = new Error(404, 'User Not Found', 'msisdn');
                }

            }

            $payload->data = $result;
            if(!empty($error))
                $payload->error = new Error(404, 'getUserByMsisdn - User Not found: ' . implode(',', $error) ,'msisdn');
        } catch(\Exception $e){
            $this->app->log->error("Yoypic: getUserByMsisdn: " . $e->getMessage());
            $payload->error = new Error((int) $e->getCode(), "getUserByMsisdn: " . $e->getMessage(), '');

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
