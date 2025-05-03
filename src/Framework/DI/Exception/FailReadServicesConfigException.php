<?php

namespace App\Framework\DI\Exception;

use Psr\Container\ContainerExceptionInterface;

class FailReadServicesConfigException extends \Exception implements ContainerExceptionInterface{}