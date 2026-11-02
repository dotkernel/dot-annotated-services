<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class NullKeyService
{
    /**
     * @Inject({"config.k"})
     */
    public function __construct(public readonly mixed $value)
    {
    }
}
