<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Inject
{
    private array $services = [];

    public function __construct(array $values)
    {
        $this->services = $values['value'] ?? [];
    }

    public function getServices()
    {
        return $this->services;
    }
}
