<?php
use Silex\Application;

$app = new Application();

$requestUri = $_SERVER['REQUEST_URI'];

$app->get(
    '/secretflying.rss',
    function (Application $app) {
        //TODO Implement
    }
);
switch (true) {
    case $requestUri === '/secretflying.rss':
        include dirname(__DIR__) . '/modules/secretflying.rss';
        break;
    case preg_match('#^/visa#', $requestUri):
        include dirname(__DIR__) . '/modules/visa.php';
        break;
    case preg_match('#^/timezone#', $requestUri):
        include dirname(__DIR__) . '/modules/timezone.php';
        break;
    default:
        include dirname(__DIR__) . '/modules/default.php';
        break;
}
