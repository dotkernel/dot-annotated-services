<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use ArrayAccess;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Exception\InvalidArgumentException;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

use function array_key_exists;
use function array_shift;
use function class_exists;
use function count;
use function explode;
use function is_array;
use function sprintf;

class AnnotatedServiceFactory extends AbstractAnnotatedFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function __invoke(ContainerInterface $container, string $requestedName): object
    {
        return $this->createObject($container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function createObject(ContainerInterface $container, string $requestedName): object
    {
        if (! class_exists($requestedName)) {
            throw RuntimeException::classNotFound($requestedName);
        }

        $annotationReader = $this->createAnnotationReader($container);
        $refClass         = $this->getReflectionClass($requestedName);
        $constructor      = $refClass->getConstructor();

        if ($constructor === null) {
            $service = new $requestedName();
        } else {
            $inject = $annotationReader->getMethodAnnotation($constructor, Inject::class);
            if (! $inject instanceof Inject && $constructor->getNumberOfRequiredParameters() > 0) {
                throw RuntimeException::annotationNotFound(
                    Inject::class,
                    $requestedName,
                    static::class
                );
            }

            $services = $inject instanceof Inject ? $this->getServicesToInject($container, $inject) : [];

            $service = new $requestedName(...$services);
        }

        foreach ($refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // The constructor was already called above with its injected services
            if ($method->isConstructor() || $method->isStatic()) {
                continue;
            }

            $inject = $annotationReader->getMethodAnnotation($method, Inject::class);
            if (! $inject instanceof Inject) {
                continue;
            }

            $method->invoke($service, ...$this->getServicesToInject($container, $inject));
        }

        return $service;
    }

    /**
     * @return list<mixed>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getServicesToInject(ContainerInterface $container, Inject $inject): array
    {
        $services = [];
        foreach ($inject->getServices() as $serviceKey) {
            $parts = explode('.', $serviceKey);
            // Even when dots are found, try to find a service with the full name
            // If it is not found, then assume dots are used to get part of an array service
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

            $services[] = $parts === [] ? $service : $this->readKeysFromArray($parts, $service);
        }

        return $services;
    }

    /**
     * @param non-empty-list<string> $keys
     * @throws InvalidArgumentException
     */
    protected function readKeysFromArray(array $keys, mixed $array): mixed
    {
        $key = array_shift($keys);

        if (is_array($array)) {
            $exists = array_key_exists($key, $array);
        } elseif ($array instanceof ArrayAccess) {
            $exists = $array->offsetExists($key);
        } else {
            $exists = false;
        }

        // Also thrown when the path is longer than the data, e.g. "config.a.b" where "a" is a scalar
        if (! $exists) {
            throw new InvalidArgumentException(sprintf(
                'The key "%s" provided in the dotted notation could not be found in the array service',
                $key
            ));
        }

        $value = $array[$key];
        if ($keys === []) {
            return $value;
        }

        return $this->readKeysFromArray($keys, $value);
    }

    /**
     * @param class-string $requestedName
     * @return ReflectionClass<object>
     * @throws ReflectionException
     */
    protected function getReflectionClass(string $requestedName): ReflectionClass
    {
        return new ReflectionClass($requestedName);
    }
}
