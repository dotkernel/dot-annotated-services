<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"CLASS"})
 */
class Entity
{
    /**
     * @Required
     * @var class-string
     */
    private string $name;

    /**
     * @param class-string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return class-string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
