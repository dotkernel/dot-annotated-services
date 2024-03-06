<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\TestData;

use Dot\AnnotatedServices\Attribute\Inject;

class RecursionService
{
    #[Inject(
        self::class,
    )]
    public function __construct(
        protected ?RecursionService $service = null
    ) {
    }
}
