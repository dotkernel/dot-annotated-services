<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use DomainException;

class ThrowingConstructorService
{
    public function __construct()
    {
        throw new DomainException('constructor failed');
    }
}
