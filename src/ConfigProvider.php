<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices;

use Dot\AnnotatedServices\Factory\AnnotatedServiceAbstractFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependenciesConfig(),
        ];
    }

    public function getDependenciesConfig(): array
    {
        return [
            'abstract_factories' => [
                AnnotatedServiceAbstractFactory::class,
            ],
        ];
    }
}
