<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AnnotatedRepositoryFactory;
use DotTest\AnnotatedServices\Fixture\ExampleEntity;
use DotTest\AnnotatedServices\Fixture\ExampleRepository;
use DotTest\AnnotatedServices\Fixture\PlainClass;
use DotTest\AnnotatedServices\Fixture\RepositoryWithoutAnnotation;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AnnotatedRepositoryFactory::class)]
class AnnotatedRepositoryFactoryTest extends TestCase
{
    private AnnotatedRepositoryFactory $subject;

    protected function setUp(): void
    {
        $this->subject = new AnnotatedRepositoryFactory();
    }

    private function container(EntityManagerInterface $entityManager): ServiceManager
    {
        return new ServiceManager(['services' => [EntityManagerInterface::class => $entityManager]]);
    }

    public function testThrowsExceptionClassNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound('TestRepository')->getMessage());

        ($this->subject)($this->container($this->createStub(EntityManagerInterface::class)), 'TestRepository');
    }

    public function testThrowsExceptionClassDoesNotExtendEntityRepository(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::doesNotExtend(EntityRepository::class)->getMessage());

        ($this->subject)($this->container($this->createStub(EntityManagerInterface::class)), PlainClass::class);
    }

    public function testThrowsExceptionWhenEntityAnnotationIsMissing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::annotationNotFound(
            Entity::class,
            RepositoryWithoutAnnotation::class,
            AnnotatedRepositoryFactory::class
        )->getMessage());

        ($this->subject)(
            $this->container($this->createStub(EntityManagerInterface::class)),
            RepositoryWithoutAnnotation::class
        );
    }

    public function testReturnsRepositoryFromEntityManager(): void
    {
        $repository    = $this->createStub(ExampleRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(ExampleEntity::class)
            ->willReturn($repository);

        $result = ($this->subject)($this->container($entityManager), ExampleRepository::class);

        $this->assertSame($repository, $result);
    }

    public function testThrowsExceptionWhenEntityManagerReturnsADifferentRepositoryClass(): void
    {
        $repository    = $this->createStub(EntityRepository::class);
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $entityManager->method('getRepository')->willReturn($repository);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            RuntimeException::repositoryMismatch(ExampleRepository::class, $repository::class)->getMessage()
        );

        ($this->subject)($this->container($entityManager), ExampleRepository::class);
    }
}
