<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotates a class as a service which can be created and injected by this library
 *
 * @Annotation
 * @Annotation\Target({"CLASS"})
 */
class Service
{
}
