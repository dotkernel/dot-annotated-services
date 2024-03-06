<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\TestData;

use Dot\AnnotatedServices\Attribute\Inject;

class ValidService
{
    #[Inject(
        Entity::class,
        "config.uration",
    )]
    public function __construct(
        protected ?Entity $service = null
    ) {
    }
}
