<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/app/init.php';

if (preg_match('#^/timezone#', $requestUri)) {
    include dirname(__DIR__) . '/modules/timezone.php';
}