<?php
define('APP_PATH', dirname(__DIR__)); // PHP v5.3+

require '../vendor/autoload.php';

define("USER_AUTHORIZED", true);
define("APP_NAME", 'YOYPIC API');

/*
// Prepare app
$app = new \Slim\Slim(array(
    'mode' => 'development',
    'templates.path' => '../app/views',
));
*/

// init app
$app = new \RKA\Slim(array(
    'mode' => 'development',
    'templates.path' => '../app/views',
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,

        // database settings
        'pdo' => [
            'dsn' => 'mysql:host=localhost;dbname=notes;charset=utf8',
            'username' => 'notes',
            'password' => 'notes',
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__.'/../log/app.log',
        ],

        // Firebase settings
        'firebase' => [
            'default_url' => 'https://encryption-8063c.firebaseio.com/',
            'default_token' => 'fu2UJTt05isGKunDRfMmoGbC8hV7nNJ8ZxelDqOQ',
            'default_path' => '/users',
            'default_fcm_url' => 'https://fcm.googleapis.com/fcm/send',
       ],
    ],

));

require '../app/config/config.php';

// Create monolog logger and store logger in container as singleton 
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Firebase
$app->container->singleton('firebase', function () {
    $app = \RKA\Slim::getInstance();
    $settings = $app->container->get('settings');

    return new \Firebase\FirebaseLib($settings['settings']['firebase']['default_url'], $settings['settings']['firebase']['default_token']);
});

// Guzzle
$app->container->singleton('guzzle', function(){
    $app = \RKA\Slim::getInstance();
    $settings = $app->container->get('settings');

    return new GuzzleHttp\Client([
                                    'verify' => false,
                                    'base_url' => $settings['settings']['firebase']['default_fcm_url']
                                 ]);
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../app/views/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Repositories
$app->container->singleton('userRepository', function ($c) {
    return new \App\repositories\UserRepository($c);
});

$app->container->singleton('tokenRepository', function ($c) {
    return new \App\repositories\TokenRepository($c);
});


// Controllers
$app->container->singleton('\App\Home', function ($c) {
    return new \App\controllers\Home();
});

$app->container->singleton('\App\Users', function ($c) {

    return new \App\controllers\Users($c);
});

$app->container->singleton('\App\Token', function ($c) {

    return new \App\controllers\Tokens($c);
});

$app->container->singleton('\App\Notifications', function ($c) {

    return new \App\controllers\Notifications($c);
});

// Routes
require '../app/routes/routes.php';

// Run app
$app->run();
