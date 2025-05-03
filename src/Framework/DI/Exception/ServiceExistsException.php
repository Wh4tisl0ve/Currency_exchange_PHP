<?php

namespace App\Framework\DI\Exception;

use Psr\Container\ContainerExceptionInterface;

class ServiceExistsException extends \Exception implements ContainerExceptionInterface{}