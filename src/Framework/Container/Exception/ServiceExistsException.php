<?php

namespace App\Framework\Container\Exception;

use Psr\Container\ContainerExceptionInterface;

class ServiceExistsException extends \Exception implements ContainerExceptionInterface{}