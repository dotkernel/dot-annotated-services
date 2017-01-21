<?php
/**
 * @copyright: DotKernel
 * @library: dot-annotated-services
 * @author: n3vrax
 * Date: 1/21/2017
 * Time: 12:16 AM
 */

namespace Dot\AnnotatedServices\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Exception\InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Dot\AnnotatedServices\Exception\RuntimeException;

/**
 * Class AnnotatedServiceFactory
 * @package Dot\AnnotatedServiced\Factory
 */
class AnnotatedServiceFactory
{
    /** @var  Reader */
    protected $annotationReader;


    public function __invoke(ContainerInterface $container, $requestedName)
    {
        return $this->createObject($container, $requestedName);
    }

    public function createObject(ContainerInterface $container, $requestedName)
    {
        if (! class_exists($requestedName)) {
            throw new RuntimeException(sprintf(
                'Annotated factories can only be used with services that are identified by their FQCN. ' .
                'Provided "%s" service name is not a valid class.',
                $requestedName
            ));
        }

        $annotationReader = $this->createAnnotationReader();
        $refClass = new \ReflectionClass($requestedName);
        $constructor = $refClass->getConstructor();
        if ($constructor === null) {
            return new $requestedName();
        }

        $inject = $annotationReader->getMethodAnnotation($constructor, Inject::class);
        if ($inject === null) {
            throw new RuntimeException(sprintf(
                'You need to use the "%s" annotation in "%s" constructor so that the "%s" can create it.',
                Inject::class,
                $requestedName,
                static::class
            ));
        }

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
            if (! $container->has($serviceKey)) {
                throw new RuntimeException(sprintf(
                    'Defined injectable service "%s" could not be found in container.',
                    $serviceKey
                ));
            }
            $service = $container->get($serviceKey);
            $services[] = empty($parts) ? $service : $this->readKeysFromArray($parts, $service);
        }
        return new $requestedName(...$services);
    }

    /**
     * @param array $keys
     * @param $array
     * @return mixed
     */
    protected function readKeysFromArray(array $keys, $array)
    {
        $key = array_shift($keys);
        // When one of the provided keys is not found, thorw an exception
        if (! isset($array[$key])) {
            throw new InvalidArgumentException(sprintf(
                'The key "%s" provided in the dotted notation could not be found in the array service',
                $key
            ));
        }
        $value = $array[$key];
        if (! empty($keys) && (is_array($value) || $value instanceof \ArrayAccess)) {
            $value = $this->readKeysFromArray($keys, $value);
        }
        return $value;
    }

    /**
     * @return AnnotationReader|Reader
     */
    protected function createAnnotationReader()
    {
        //TODO: check if cache is enabled and available

        if ($this->annotationReader !== null) {
            return $this->annotationReader;
        }

        AnnotationRegistry::registerLoader('class_exists');
        return $this->annotationReader = new AnnotationReader();
    }

    /**
     * @param Reader $annotationReader
     * @return $this
     */
    public function setAnnotationReader(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
        return $this;
    }
}
