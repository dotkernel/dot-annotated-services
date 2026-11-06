<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices;

/**
 * Kept so that applications which add it to their configuration aggregator keep working.
 * The factories are registered per class in your own configuration, there is nothing to register globally.
 */
class ConfigProvider
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [];
    }
}
