<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class ContainerFailureException extends RuntimeException implements ContainerExceptionInterface
{
}
