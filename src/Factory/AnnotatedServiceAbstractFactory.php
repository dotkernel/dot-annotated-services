<?php

/**
 * @see https://github.com/dotkernel/dot-annotated-services/ for the canonical source repository
 */

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use Dot\AnnotatedServices\Annotation\Service;
use interop\container\containerinterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use ReflectionClass;

use function class_exists;

class AnnotatedServiceAbstractFactory extends AbstractAnnotatedFactory implements AbstractFactoryInterface
{
    /**
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(containerinterface $container, $requestedName)
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
     * @return object
     */
    public function __invoke(containerinterface $container, $requestedName, ?array $options = null)
    {
        $factory = new AnnotatedServiceFactory();
        $factory->setAnnotationReader($this->createAnnotationReader($container));

        return $factory->createObject($container, $requestedName);
    }
}
