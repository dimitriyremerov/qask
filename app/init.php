<?php
use Silex\Application;

$app = new Application();

$requestUri = $_SERVER['REQUEST_URI'];

$app->register(new Qask\SecretFlying\ServiceProvider());
