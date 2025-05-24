<?php

use App\Exception\ConflictException;
use MiniBox\Application;
use MiniBox\Exception\NotFoundException;
use MiniBox\Exception\ValidationException;
use MiniBox\Http\HttpParser;
use MiniBox\Http\Response\JsonResponse;


require_once __DIR__ . '/../vendor/autoload.php';


$httpRequest = HttpParser::parse($_SERVER);

$app = new Application(configRoutesPath: __DIR__ . "/../config/routes.php");
$app->registerExceptionHandler(
    function (Throwable $exception) {
        $statusCode = 500;
        $message = 'Ошибка сервера';

        if ($exception instanceof ValidationException) {
            $statusCode = 400;
            $message = $exception->getMessage();
        }
        if ($exception instanceof ConflictException) {
            $statusCode = 409;
            $message = $exception->getMessage();
        } elseif ($exception instanceof NotFoundException) {
            $statusCode = 404;
            $message = $exception->getMessage();
        } elseif ($exception instanceof PDOException) {
            $message = 'Ошибка работы БД';
        }

        $response = new JsonResponse(
            json_encode(['message' => $message], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            $statusCode,
        );

        $response->send();
    }
);

$response = $app->handle($httpRequest);
$response->send();
