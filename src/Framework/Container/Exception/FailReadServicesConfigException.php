<?php

namespace App\Framework\Container\Exception;

use Psr\Container\ContainerExceptionInterface;

class FailReadServicesConfigException extends \Exception implements ContainerExceptionInterface{}