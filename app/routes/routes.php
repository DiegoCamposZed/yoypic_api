<?php
/**
 * Created by PhpStorm.
 * User: DCampos
 * Date: 05/08/2016
 * Time: 12:36
 */

//use app\controllers\Greeting;
$app->get('/', function () use ($app) {
    // Sample log message
    $app->log->info("Yoypic '/' route");
    // Render index view
    $app->render('home.html');
});


$app->get('/home', '\App\Home:index');

/*
 * File Routes
 */
$app->get('/files/all', 'App\controllers\Files:getAllFiles');
$app->get('/files/byowner/:uid', 'App\controllers\Files:getFilesByOwner');

/*
 * User Routes
 */
$app->get('/users/subscribed/:contacts+', 'App\controllers\Users:getSubscribedContacts');
$app->post('/users/subscribed', 'App\controllers\Users:mySubscribedContacts');
$app->get('/users/msisdn/:msisdn+', 'App\controllers\Users:getUserByMsisdn');

/*
 * Token Routes
 */
$app->get('/tokens', 'App\controllers\Tokens:getCurrentTokens');
$app->get('/tokens/:uid', 'App\controllers\Tokens:getCurrentTokensByUserId');
$app->get('/tokens/msisdn/:msisdn+', 'App\controllers\Tokens:getCurrentTokensByMsisdn');

/*
 * Notification Routes
 */
$app->post('/notification', 'App\controllers\Notifications:sendNotification');

