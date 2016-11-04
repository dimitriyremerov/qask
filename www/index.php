<?php

$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/secretflying.rss') {
	include dirname(__DIR__) . '/modules/secretflying.rss';
}

if (preg_match('#^/visa#', $requestUri)) {
	include dirname(__DIR__) . '/modules/visa.php';
}

if (preg_match('#^/timezone#', $requestUri)) {
    include dirname(__DIR__) . '/modules/timezone.php';
}