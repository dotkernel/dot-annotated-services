<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Attribute;

use Attribute;

#[Attribute]
class Inject
{
    protected array $services = [];

    public function __construct(string ...$services)
    {
        $this->services = $services;
    }

    public function getServices(): array
    {
        return $this->services;
    }
}
