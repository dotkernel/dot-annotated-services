<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class MultiInjectService
{
    /**
     * Mixes a container service, a class-string and a dotted key, in a non-alphabetical order.
     *
     * @Inject({"config", Dependency::class, "settings.mode"})
     * @param array<string, mixed> $config
     */
    public function __construct(
        public readonly array $config,
        public readonly Dependency $dependency,
        public readonly string $mode,
    ) {
    }
}
