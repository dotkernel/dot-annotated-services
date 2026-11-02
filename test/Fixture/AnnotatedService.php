<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Annotation\Service;

/**
 * @Service
 */
class AnnotatedService
{
    /**
     * @Inject({"config"})
     * @param array<string, mixed> $config
     */
    public function __construct(public readonly array $config)
    {
    }
}
