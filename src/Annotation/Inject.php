<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

use function array_values;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Inject
{
    /** @var list<string> */
    private array $services = [];

    /**
     * @param array{value?: string|list<string>} $values
     */
    public function __construct(array $values)
    {
        $this->services = array_values((array) ($values['value'] ?? []));
    }

    /**
     * @return list<string>
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
