<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class ClassStringService
{
    /**
     * @Inject({Dependency::class})
     */
    public function __construct(public readonly Dependency $dependency)
    {
    }
}
