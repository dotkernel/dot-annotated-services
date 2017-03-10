<?php
/**
 * @see https://github.com/dotkernel/dot-annotated-services/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-annotated-services/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\AnnotatedServices;

use Dot\AnnotatedServices\Factory\AnnotatedServiceAbstractFactory;

/**
 * Class ConfigProvider
 * @package Dot\AnnotatedServiced
 */
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
            ]
        ];
    }
}
