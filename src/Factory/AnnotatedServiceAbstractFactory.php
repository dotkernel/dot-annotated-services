<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use Doctrine\Common\Annotations\AnnotationException;
use Dot\AnnotatedServices\Annotation\Service;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Override;
use Psr\Container\ContainerInterface;
use ReflectionClass;

use function class_exists;
use function is_string;

class AnnotatedServiceAbstractFactory extends AbstractAnnotatedFactory implements AbstractFactoryInterface
{
    private ?AnnotatedServiceFactory $serviceFactory = null;

    /**
     * @param string $requestedName
     */
    #[Override]
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        if (! is_string($requestedName) || ! class_exists($requestedName)) {
            return false;
        }

        try {
            return $this->createAnnotationReader($container)
                ->getClassAnnotation(new ReflectionClass($requestedName), Service::class) !== null;
        } catch (AnnotationException) {
            return false;
        }
    }

    /**
     * @param string $requestedName
     * @param array<array-key, mixed>|null $options
     */
    #[Override]
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        if ($this->serviceFactory === null) {
            $this->serviceFactory = new AnnotatedServiceFactory();
            $this->serviceFactory->setAnnotationReader($this->createAnnotationReader($container));
        }

        return $this->serviceFactory->createObject($container, $requestedName);
    }
}
