<?php

use App\Exception\ConflictException;
use MiniBox\Application;
use MiniBox\Exception\InvalidDataException;
use MiniBox\Exception\NotFoundException;
use MiniBox\Http\Response\JsonResponse;


require_once __DIR__ . '/../vendor/autoload.php';


$app = new Application(
    configServicesPath: __DIR__ . "/../config/service.php",
    configRoutesPath: __DIR__ . "/../config/routes.php"
);
$app->registerExceptionHandler(
    function (Throwable $exception) {
        $statusCode = 500;
        $message = 'Ошибка сервера';

        if ($exception instanceof InvalidDataException) {
            $statusCode = 400;
            $message = $exception->getMessage();
        } elseif ($exception instanceof ConflictException) {
            $statusCode = 409;
            $message = $exception->getMessage();
        } elseif ($exception instanceof NotFoundException) {
            $statusCode = 404;
            $message = $exception->getMessage();
        } else if ($exception instanceof PDOException) {
            $message = 'Ошибка работы БД';
        }

        $response = new JsonResponse(
            json_encode(['message' => $message], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            $statusCode,
        );

        $response->send();
    }
);

$response = $app->handle();
$response->send();
