<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class SetterEdgeCasesService
{
    public ?Dependency $dependency = null;
    public ?string $mode           = null;
    public bool $protectedCalled   = false;

    /**
     * @Inject({Dependency::class, "settings.mode"})
     */
    public function configure(Dependency $dependency, string $mode): void
    {
        $this->dependency = $dependency;
        $this->mode       = $mode;
    }

    /**
     * @Inject({Dependency::class})
     */
    protected function protectedSetter(Dependency $dependency): void
    {
        $this->protectedCalled = true;
    }
}
