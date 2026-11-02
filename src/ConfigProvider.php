<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices;

use Dot\AnnotatedServices\Factory\AnnotatedServiceAbstractFactory;

class ConfigProvider
{
    /**
     * @return array{dependencies: array{abstract_factories: list<class-string>}}
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependenciesConfig(),
        ];
    }

    /**
     * @return array{abstract_factories: list<class-string>}
     */
    public function getDependenciesConfig(): array
    {
        return [
            'abstract_factories' => [
                AnnotatedServiceAbstractFactory::class,
            ],
        ];
    }
}
