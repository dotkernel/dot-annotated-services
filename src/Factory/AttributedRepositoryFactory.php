<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Attribute\Entity;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;

use function class_exists;

class AttributedRepositoryFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName): EntityRepository
    {
        return $this->createObject($container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createObject(ContainerInterface $container, string $requestedName): EntityRepository
    {
        if (! class_exists($requestedName)) {
            throw RuntimeException::classNotFound($requestedName);
        }

        $reflectionClass = new ReflectionClass($requestedName);
        if (! $reflectionClass->isSubclassOf(EntityRepository::class)) {
            throw RuntimeException::doesNotExtend($requestedName, EntityRepository::class);
        }

        $entityAttribute = $this->findEntityAttribute($reflectionClass);
        if (! $entityAttribute instanceof Entity) {
            throw RuntimeException::attributeNotFound(Entity::class, $requestedName, static::class);
        }

        return $container->get(EntityManagerInterface::class)->getRepository($entityAttribute->getName());
    }

    protected function findEntityAttribute(ReflectionClass $reflectionClass): ?Entity
    {
        $attributes = $reflectionClass->getAttributes();
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === Entity::class) {
                return $attribute->newInstance();
            }
        }

        return null;
    }
}
