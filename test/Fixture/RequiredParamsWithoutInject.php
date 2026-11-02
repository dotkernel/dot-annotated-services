<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

class RequiredParamsWithoutInject
{
    public function __construct(public readonly string $name)
    {
    }
}
