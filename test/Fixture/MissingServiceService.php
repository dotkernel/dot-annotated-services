<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class MissingServiceService
{
    /**
     * @Inject({"missing_service"})
     */
    public function __construct(public readonly mixed $value)
    {
    }
}
