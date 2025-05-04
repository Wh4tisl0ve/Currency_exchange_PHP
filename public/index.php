<?php

use App\Framework\Application;
use App\Framework\Http\HttpParser;

require_once __DIR__ . '/../vendor/autoload.php';


$httpRequest = HttpParser::parse($_SERVER);

$app = new Application();

header('Content-Type: application/json; charset=utf-8');
$app->handle($httpRequest);
#$response->send();
