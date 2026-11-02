<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

class OptionalParamsService
{
    public function __construct(public readonly string $name = 'default')
    {
    }
}
