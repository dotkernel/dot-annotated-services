<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class UnknownRootKeyService
{
    /**
     * @Inject({"unknown_root.key"})
     */
    public function __construct(public readonly mixed $value)
    {
    }
}
