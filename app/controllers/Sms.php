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

    public function sendSmsClickatell($to, $content)
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

    public function sendSmsTwilio($to, $content)
    {


        if (!empty($to) && !empty($content)) {

            $id = "AC2d26c8ab9f65aad99b8574f71f81b7b3";
            $token = "6f8aec52823dc699f4afc4acb6142aed";
            $messagingServiceSid="MGe50a4fcc22149b62222f1f9a9d5a30ed";
            $from = "+17868013845";
            $url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages.json";

            $requestBody = array();
            $requestBody['From']                = $from;
            $requestBody['To']                  = $to;
            $requestBody['Body']                = $content;
            $requestBody['MessagingServiceSid'] = $messagingServiceSid;

            $encodedAuth = base64_encode($id . ':' . $token);
            try {
                $request = $this->app->guzzle->post($url,
                    [
                        'Authorization' => 'Basic ' . $encodedAuth,
                        'Content-Type'  => 'application/json; charset=utf-8'
                    ],
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

        if($this->app->request()->params('to') != null && $this->app->request()->params('text') != null){
            $to = $this->app->request()->params('to');
            $text = $this->app->request()->params('text');

            // Clickatell platform
//            $result = $this->sendSmsClickatell($to, $text);

            // Twilio platform
            $result = $this->sendSmsTwilio($to, $text);

            $this->app->log->error(APP_NAME . " sendCustomSms : RESULT - " . $result);

            $payload->data = json_decode($result);

        } else {
            $body = $this->app->request()->getBody();
            $bodyObj = json_decode($body);
            if($bodyObj!= null){
//                $result = $this->sendSms($bodyObj->to, $bodyObj->text);
                $result = $this->sendSmsTwilio($bodyObj->to, $bodyObj->text);

                $this->app->log->info(APP_NAME . " sendCustomSms : RESULT - " . $result);

                $payload->data = json_decode($result);

            } else {
                $this->app->log->error(APP_NAME . " sendCustomSms : Empty body");
                $payload->error = new Error(400, " sendCustomSms : Empty body", '');
            }

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
