<?php

spl_autoload_register(function ($className) {
    $filename = str_replace("App", "src", $className . '.php');
    $filepath = str_replace("\\", '/',__DIR__ . "/$filename");

    if (file_exists($filepath)) {
        require_once $filepath;
    } else {
        echo "Файл для класса $className не найден по пути $filepath" . PHP_EOL;
    }
});
