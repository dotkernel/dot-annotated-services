<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Annotates a class as a service which can be created and injected by this library
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class Service
{
}
