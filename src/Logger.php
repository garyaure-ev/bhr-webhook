<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (!function_exists('getLogger')) {
    function getLogger(): Logger {
        $log = new Logger('webhook');
        $log->pushHandler(new StreamHandler(__DIR__ . '/../webhook.log', Logger::INFO));
        return $log;
    }
}
