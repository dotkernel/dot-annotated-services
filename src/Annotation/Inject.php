<?php

/**
 * @see https://github.com/dotkernel/dot-annotated-services/ for the canonical source repository
 */

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
    /** @var  array */
    private $services = [];

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->services = $values['value'] ?? [];
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
