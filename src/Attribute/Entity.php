<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Attribute;

use Attribute;

#[Attribute]
class Entity
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
