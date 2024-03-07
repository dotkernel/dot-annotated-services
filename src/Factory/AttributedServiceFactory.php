<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use ArrayAccess;
use Dot\AnnotatedServices\Attribute\Inject;
use Dot\AnnotatedServices\Exception\InvalidArgumentException;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionMethod;

use function array_shift;
use function class_exists;
use function count;
use function explode;
use function in_array;
use function is_array;
use function sprintf;

class AttributedServiceFactory
{
    protected string $originalKey;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName): mixed
    {
        return $this->createObject($container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createObject(ContainerInterface $container, string $requestedName): mixed
    {
        if (! class_exists($requestedName)) {
            throw RuntimeException::classNotFound($requestedName);
        }

        $constructor = (new ReflectionClass($requestedName))->getConstructor();
        if ($constructor === null) {
            return new $requestedName();
        }

        $injectAttribute = $this->findInjectAttribute($constructor);
        if (! $injectAttribute instanceof Inject) {
            throw RuntimeException::attributeNotFound(Inject::class, $requestedName, static::class);
        }

        if (in_array($requestedName, $injectAttribute->getServices(), true)) {
            throw RuntimeException::recursiveInject($requestedName);
        }

        $services = $this->getServicesToInject($container, $injectAttribute->getServices());

        return new $requestedName(...$services);
    }

    protected function findInjectAttribute(ReflectionMethod $constructor): ?Inject
    {
        $attributes = $constructor->getAttributes();
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === Inject::class) {
                return $attribute->newInstance();
            }
        }

        return null;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getServicesToInject(ContainerInterface $container, array $parameters): array
    {
        $services = [];

        foreach ($parameters as $parameter) {
            $services[] = $this->getServiceToInject($container, $parameter);
        }

        return $services;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getServiceToInject(ContainerInterface $container, string $serviceKey): mixed
    {
        $this->originalKey = $serviceKey;

        /**
         * Even when dots are found, try to find a service with the full name.
         * If it is not found, then assume dots are used to get part of an array service
         */
        $parts = explode('.', $serviceKey);
        if (count($parts) > 1 && ! $container->has($serviceKey)) {
            $serviceKey = array_shift($parts);
        } else {
            $parts = [];
        }

        if ($container->has($serviceKey)) {
            $service = $container->get($serviceKey);
        } elseif (class_exists($serviceKey)) {
            $service = new $serviceKey();
        } else {
            throw RuntimeException::classNotFound($serviceKey);
        }

        return empty($parts) ? $service : $this->readKeysFromArray($parts, $service);
    }

    protected function readKeysFromArray(array $keys, mixed $array): mixed
    {
        $key = array_shift($keys);
        if (! isset($array[$key])) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::MESSAGE_MISSING_KEY, $this->originalKey)
            );
        }

        $value = $array[$key];
        if (! empty($keys) && (is_array($value) || $value instanceof ArrayAccess)) {
            $value = $this->readKeysFromArray($keys, $value);
        }

        return $value;
    }
}
