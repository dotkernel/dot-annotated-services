<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class ServiceNotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
}
