<?php

use App\CurrencyExchange\Exception\ConflictException;
use App\Framework\Application;
use App\Framework\Exception\NotFoundException;
use App\Framework\Exception\ValidationException;
use App\Framework\Http\HttpParser;
use App\Framework\Http\Response\JsonResponse;


require_once __DIR__ . '/../vendor/autoload.php';


$httpRequest = HttpParser::parse($_SERVER);

$app = new Application();
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
