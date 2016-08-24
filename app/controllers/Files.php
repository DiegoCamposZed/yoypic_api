<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 10/08/2016
 * Time: 11:14
 */

namespace App\controllers;

use App\models\File;

class Files
{
    protected $app;
    protected $request;
    protected $response;

    public function index()
    {
        echo "This is the home page";
    }

    public function getAllFiles()
    {

        try{

            $json = json_decode($this->app->firebase->get('files/all', array('orderBy' => '"ownerId"')));

            $files = array();
            if ($json != null) {
                $last = '';
                foreach ($json as $key => $file) {
                    $id         = $key;
                    $message    = (isset($file->message) ? trim($file->message) : '');
                    $ownerId    = (isset($file->ownerId) ? trim($file->ownerId) : '');
                    $title      = (isset($file->title) ? trim($file->title) : '');
                    $url        = (isset($file->url) ? trim($file->url) : '');
                    $createdAt  = (isset($file->createdAt) ? $file->createdAt : '');
                    $updatedAt  = (isset($file->updatedAt) ? $file->updatedAt : '');
                    $acl        = (isset($file->acl) ? $file->acl : '');
                    $localPaths = (isset($file->localPaths) ? $file->localPaths : '');
                    $interactions = (isset($file->interactions) ? $file->interactions : '');

                    /*
                    if($last < $ownerId){
                        echo 'last --> ' . $ownerId . '<br>';
                        $last = $ownerId;
                    }
    */
                    $files[] = new File($id, $message, $ownerId, $title, $url,
                                       $createdAt, $updatedAt, $acl, $localPaths, $interactions);
                }
            }
            $payload = new \stdClass();
            $payload->data = $files;
        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getAllFiles: " . $e->getMessage());

        }

        $this->response->headers->set('Content-Type', 'application/json');
        echo json_encode($payload);


    }

    public function getFilesByOwner($uid)
    {

        try{

            $json = json_decode($this->app->firebase->get('files/byowner/' . $uid, array( )));

            $files = array();
            if ($json != null) {
                $last = '';
                foreach ($json as $key => $file) {
                    $id         = $key;
                    $message    = (isset($file->message) ? trim($file->message) : '');
                    $ownerId    = (isset($file->ownerId) ? trim($file->ownerId) : '');
                    $title      = (isset($file->title) ? trim($file->title) : '');
                    $url        = (isset($file->url) ? trim($file->url) : '');
                    $createdAt  = (isset($file->createdAt) ? $file->createdAt : '');
                    $updatedAt  = (isset($file->updatedAt) ? $file->updatedAt : '');
                    $acl        = (isset($file->acl) ? $file->acl : '');
                    $localPaths = (isset($file->localPaths) ? $file->localPaths : '');
                    $interactions = (isset($file->interactions) ? $file->interactions : '');

                    $files[] = new File($id, $message, $ownerId, $title, $url,
                        $createdAt, $updatedAt, $acl, $localPaths, $interactions);
                }
            }
            $payload = new \stdClass();
            $payload->data = $files;
        } catch(\Exception $e){
            $this->app->log->error(APP_NAME . " getAllFiles: " . $e->getMessage());

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
