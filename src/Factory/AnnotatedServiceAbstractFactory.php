<?php
/**
 * @copyright: DotKernel
 * @library: dot-annotated-services
 * @author: n3vrax
 * Date: 1/21/2017
 * Time: 12:05 AM
 */

namespace Dot\AnnotatedServices\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Dot\AnnotatedServices\Annotation\Service;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class AnnotatedServiceAbstractFactory
 * @package Dot\AnnotatedServiced\Factory
 */
class AnnotatedServiceAbstractFactory implements AbstractFactoryInterface
{
    /** @var  Reader */
    protected $annotationReader;

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

        $annotationReader = $this->createAnnotationReader();
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
        $factory->setAnnotationReader($this->createAnnotationReader());

        return $factory->createObject($container, $requestedName);
    }

    /**
     * @return AnnotationReader|Reader
     */
    protected function createAnnotationReader()
    {
        if ($this->annotationReader !== null) {
            return $this->annotationReader;
        }

        AnnotationRegistry::registerLoader('class_exists');
        return $this->annotationReader = new AnnotationReader();
    }
}
