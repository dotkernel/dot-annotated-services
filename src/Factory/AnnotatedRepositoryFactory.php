<?php

declare(strict_types = 1);

namespace Dot\AnnotatedServices\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionClass;

/**
 * Class AnnotatedRepositoryFactory
 * @package Dot\AnnotatedServices\Factory
 */
class AnnotatedRepositoryFactory extends AbstractAnnotatedFactory
{
    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return EntityRepository
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function __invoke(ContainerInterface $container, $requestedName): EntityRepository
    {
        return $this->createObject($container, $requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return EntityRepository
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function createObject(ContainerInterface $container, $requestedName): EntityRepository
    {
        if (! class_exists($requestedName)) {
            throw RuntimeException::classNotFound($requestedName);
        }

        $reflectionClass = new ReflectionClass($requestedName);
        if (! $reflectionClass->isSubclassOf(EntityRepository::class)) {
            throw RuntimeException::doesNotExtend(EntityRepository::class);
        }

        $annotationReader = $this->createAnnotationReader($container);
        $entity = $annotationReader->getClassAnnotation($reflectionClass, Entity::class);
        if (! $entity) {
            throw RuntimeException::annotationNotFound(Entity::class, $requestedName, static::class);
        }

        $entityManager = $container->get(EntityManagerInterface::class);
        return $entityManager->getRepository($entity->getName());
    }
}
