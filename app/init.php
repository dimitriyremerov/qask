<?php
use Silex\Application;
define('PROJECT_ROOT', dirname(__DIR__));

$app = new Application();

$requestUri = $_SERVER['REQUEST_URI'];

$app->register(new \Qask\SecretFlying\ServiceProvider());
$app->register(new \Qask\Visa\ServiceProvider());
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => PROJECT_ROOT . '/views',
]);

$app->run();
