<?php

declare(strict_types = 1);

namespace Dot\AnnotatedServices\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * Class Entity
 * @package Dot\AnnotatedServices\Annotation
 *
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"CLASS"})
 */
class Entity
{
    /** @Required */
    private string $name;

    /**
     * Entity constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
