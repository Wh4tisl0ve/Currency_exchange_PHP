<?php

require_once '../autoload.php';

use App\DI\ContainerDI;


$container = new ContainerDI(__DIR__ . '/../config/');
$container->compile();