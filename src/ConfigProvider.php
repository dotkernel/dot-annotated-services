<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-annotated-services
 * @author: n3vrax
 * Date: 1/20/2017
 * Time: 4:38 PM
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
