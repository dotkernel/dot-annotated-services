<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Closure;
use Psr\Container\ContainerInterface;

use function array_key_exists;
use function sprintf;

/**
 * Minimal PSR-11 container, so the tests need nothing but psr/container.
 */
class ArrayContainer implements ContainerInterface
{
    /**
     * @param array<string, mixed>   $services  Ready-made services
     * @param array<string, Closure> $factories Called on every get()
     */
    public function __construct(
        private readonly array $services = [],
        private readonly array $factories = [],
    ) {
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        if (isset($this->factories[$id])) {
            return ($this->factories[$id])();
        }

        throw new ServiceNotFoundException(sprintf('Service "%s" was not found', $id));
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services) || isset($this->factories[$id]);
    }
}
