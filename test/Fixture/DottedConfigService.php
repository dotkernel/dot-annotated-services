<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class DottedConfigService
{
    /**
     * @Inject({"config.a.b"})
     */
    public function __construct(public readonly mixed $value)
    {
    }
}
