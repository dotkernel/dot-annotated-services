<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class MissingSetterDependencyService
{
    /**
     * @Inject({"missing_setter_service"})
     */
    public function setMissing(mixed $value): void
    {
    }
}
