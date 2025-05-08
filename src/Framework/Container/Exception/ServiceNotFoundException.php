<?php

namespace App\Framework\Container\Exception;

use App\Framework\Exception\NotFoundException;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends NotFoundException implements NotFoundExceptionInterface{}