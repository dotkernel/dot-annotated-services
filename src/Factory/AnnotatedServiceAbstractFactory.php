<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use Dot\AnnotatedServices\Annotation\Service;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;

use function class_exists;

class AnnotatedServiceAbstractFactory extends AbstractAnnotatedFactory implements AbstractFactoryInterface
{
    /**
     * @param string $requestedName
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        if ($requestedName === null) {
            return false;
        }

        if (! class_exists($requestedName)) {
            return false;
        }

        $annotationReader = $this->createAnnotationReader($container);
        $refClass         = new ReflectionClass($requestedName);

        $service = $annotationReader->getClassAnnotation($refClass, Service::class);
        if ($service === null) {
            return false;
        }

        return true;
    }

    /**
     * @param string $requestedName
     * @param array|null $options
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $factory = new AnnotatedServiceFactory();
        $factory->setAnnotationReader($this->createAnnotationReader($container));

        return $factory->createObject($container, $requestedName);
    }
}
