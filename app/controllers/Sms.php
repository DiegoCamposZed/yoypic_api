<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 05/09/2016
 * Time: 17:20
 */
namespace App\controllers;

use App\models\Error;

class Sms
{
    protected $request;
    protected $response;

    public function sendSms($to, $content)
    {


        if (!empty($to) && !empty($content)) {

            $requestBody = array();
            $requestBody['user']     = 'yoypic';
            $requestBody['password'] = 'ysemlda2016';
            $requestBody['api_id']   = '3614912';
            $requestBody['from']     = 'Yoypic';
            $requestBody['to']       = $to;
            $requestBody['text']     = $content;

            try {
                 $request = $this->app->guzzle->post('http://api.clickatell.com/http/sendmsg',
                                ['Content-Type'  => 'text/plain; charset=utf-8'],
                                $requestBody,
                                []
                            );

                $response = $request->send();


                $this->app->log->info(APP_NAME . " Send SMS: to: " . $to . " Text: " . $content . " - Result: " . $response);

                return $response->getBody();

            } catch (RequestException $e) {

                $this->app->log->error(APP_NAME . " Send Notification ERROR REQUEST : " .$e->getRequest());

            }
        }
        return null;

    }

    public function sendCustomSms(){
        $payload = new \stdClass();

        $body = $this->app->request()->getBody();

        if (!empty($body)) {
            try {
                $bodyObj = json_decode($body);

                if($bodyObj != null && property_exists($bodyObj, 'to') && property_exists($bodyObj, 'content')){
                    $to      = $bodyObj->to;
                    $text = $bodyObj->content;

                    $result = $this->sendSms($to, $text);

                    $payload->data = (string) $result;
                } else {
                    $this->app->log->error(APP_NAME . " sendCustomSms : Bad request ");
                    $payload->error = new Error(400, " sendCustomms : Bad request", '');
                }
            } catch (RequestException $e) {

                $this->app->log->error(APP_NAME . " sendCustomSms : Server Error " . $e->getRequest());
                $payload->error = new Error(500, " Server Error ", '');

            }
        } else {
            $this->app->log->error(APP_NAME . " sendCustomSms : Empty body");
            $payload->error = new Error(400, " sendCustomSms : Empty body", '');

        }
        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);

    }

    public function sendUserRegisteredSms(){
        $payload = new \stdClass();

        $body = $this->app->request()->getBody();

        if (!empty($body)) {
            try {
                $bodyObj = json_decode($body);

                if($bodyObj != null && property_exists($bodyObj, 'to') && property_exists($bodyObj, 'code')){
                    $to      = $bodyObj->to;
                    $code    = $bodyObj->code;

                    $lang    = '';
                    if(property_exists($bodyObj, 'lang'))
                        $lang    = $bodyObj->lang;

                    switch($lang){
                        case "en" :
                            $content = 'Your Validation code: ' . $code;
                            break;

                        case "es":
                            $content = 'Código de Validación: ' . $code;
                            break;

                        default:
                            $content = $code;
                            break;
                    }

                    $text = $content;

                    $result = $this->sendSms($to, $text);

                    $payload->data = (string) $result;
                } else {
                    $this->app->log->error(APP_NAME . " sendUserRegisteredSms : Bad request ");
                    $payload->error = new Error(400, " sendUserRegisteredSms : Bad request", '');
                }
            } catch (RequestException $e) {

                $this->app->log->error(APP_NAME . " sendUserRegisteredSms : Server Error " . $e->getRequest());
                $payload->error = new Error(500, " Server Error ", '');

            }
        } else {
            $this->app->log->error(APP_NAME . " sendUserRegisteredSms : Empty body");
            $payload->error = new Error(400, " sendUserRegisteredSms : Empty body", '');

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
