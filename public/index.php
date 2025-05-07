<?php

use App\Framework\Application;
use App\Framework\Http\HttpParser;

require_once __DIR__ . '/../vendor/autoload.php';


$httpRequest = HttpParser::parse($_SERVER);

$app = new Application();

$response = $app->handle($httpRequest);
$response->send();
