<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Dot\AnnotatedServices\Attribute\Entity;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AttributedRepositoryFactory;
use DotTest\AnnotatedServices\TestData\Entity as TestEntity;
use DotTest\AnnotatedServices\TestData\Repository as TestRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function sprintf;

class AttributedRepositoryFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionIfClassNotFound(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(RuntimeException::MESSAGE_CLASS_NOT_FOUND, 'test')
        );

        (new AttributedRepositoryFactory())($container, 'test');
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionIfRepositoryDoesNotExtendEntityRepository(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $subject = new class {
        };

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(RuntimeException::MESSAGE_DOES_NOT_EXTEND, $subject::class, EntityRepository::class)
        );

        (new AttributedRepositoryFactory())($container, $subject::class);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionIfAttributeNotFound(): void
    {
        $container     = $this->createMock(ContainerInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entity   = new class {
        };
        $metadata = new ClassMetadata($entity::class);
        $subject  = new class ($entityManager, $metadata) extends EntityRepository {
        };

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(
                RuntimeException::MESSAGE_ATTRIBUTE_NOT_FOUND,
                Entity::class,
                $subject::class,
                AttributedRepositoryFactory::class
            )
        );

        (new AttributedRepositoryFactory())($container, $subject::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testWillCreateRepository(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $container     = $this->createMock(ContainerInterface::class);

        $entity   = new TestEntity();
        $metadata = new ClassMetadata($entity::class);
        $subject  = new TestRepository($entityManager, $metadata);

        $container
            ->expects($this->once())
            ->method('get')
            ->with(EntityManagerInterface::class)
            ->willReturn($entityManager);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(TestEntity::class)
            ->willReturn($subject);

        $repository = (new AttributedRepositoryFactory())($container, TestRepository::class);
        $this->assertInstanceOf(TestRepository::class, $repository);
    }
}
