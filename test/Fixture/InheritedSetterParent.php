<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class InheritedSetterParent
{
    public ?Dependency $dependency = null;

    /**
     * @Inject({Dependency::class})
     */
    public function setDependency(Dependency $dependency): void
    {
        $this->dependency = $dependency;
    }
}
