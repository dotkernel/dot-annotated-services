<?php
/**
 * @copyright: DotKernel
 * @library: dot-annotated-services
 * @author: n3vrax
 * Date: 1/21/2017
 * Time: 12:05 AM
 */

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use Dot\AnnotatedServices\Annotation\Service;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class AnnotatedServiceAbstractFactory
 * @package Dot\AnnotatedServiced\Factory
 */
class AnnotatedServiceAbstractFactory extends AbstractAnnotatedFactory implements AbstractFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (! class_exists($requestedName)) {
            return false;
        }

        $annotationReader = $this->createAnnotationReader($container);
        $refClass = new \ReflectionClass($requestedName);

        $service = $annotationReader->getClassAnnotation($refClass, Service::class);
        if ($service === null) {
            return false;
        }

        return true;
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $factory = new AnnotatedServiceFactory();
        $factory->setAnnotationReader($this->createAnnotationReader($container));

        return $factory->createObject($container, $requestedName);
    }
}
