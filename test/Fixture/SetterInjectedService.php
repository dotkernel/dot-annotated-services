<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Dot\AnnotatedServices\Annotation\Inject;

class SetterInjectedService
{
    public static int $constructed = 0;
    public static int $staticCalls = 0;

    public ?Dependency $dependency = null;

    /**
     * Promoted readonly property: calling the constructor twice would throw.
     *
     * @Inject({"config"})
     * @param array<string, mixed> $config
     */
    public function __construct(public readonly array $config)
    {
        self::$constructed++;
    }

    /**
     * @Inject({Dependency::class})
     */
    public function setDependency(Dependency $dependency): void
    {
        $this->dependency = $dependency;
    }

    /**
     * @Inject({Dependency::class})
     */
    public static function staticSetter(Dependency $dependency): void
    {
        self::$staticCalls++;
    }
}
