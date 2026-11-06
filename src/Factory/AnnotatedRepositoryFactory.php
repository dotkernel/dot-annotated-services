<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

use function class_exists;

class AnnotatedRepositoryFactory extends AbstractAnnotatedFactory
{
    /**
     * @return EntityRepository<object>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function __invoke(ContainerInterface $container, string $requestedName): EntityRepository
    {
        return $this->createObject($container, $requestedName);
    }

    /**
     * @return EntityRepository<object>
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function createObject(ContainerInterface $container, string $requestedName): EntityRepository
    {
        if (! class_exists($requestedName)) {
            throw RuntimeException::classNotFound($requestedName);
        }

        $reflectionClass = new ReflectionClass($requestedName);
        if (! $reflectionClass->isSubclassOf(EntityRepository::class)) {
            throw RuntimeException::doesNotExtend(EntityRepository::class);
        }

        $entity = $this->createAnnotationReader($container)->getClassAnnotation($reflectionClass, Entity::class);
        if (! $entity instanceof Entity) {
            throw RuntimeException::annotationNotFound(Entity::class, $requestedName, static::class);
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);
        $repository    = $entityManager->getRepository($entity->getName());

        if (! $repository instanceof $requestedName) {
            throw RuntimeException::repositoryMismatch($requestedName, $repository::class);
        }

        return $repository;
    }
}
