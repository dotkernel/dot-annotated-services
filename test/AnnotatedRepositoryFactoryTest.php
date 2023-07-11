<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AnnotatedRepositoryFactory as Subject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function get_class;

class AnnotatedRepositoryFactoryTest extends TestCase
{
    private ContainerInterface $container;

    private Subject $subject;

    private Reader $annotationReader;

    public function setUp(): void
    {
        $this->container        = $this->createMock(ContainerInterface::class);
        $this->annotationReader = $this->createMock(Reader::class);
        $this->subject          = $this->createPartialMock(Subject::class, ['createAnnotationReader']);
    }

    public function testThrowsExceptionClassNotFound()
    {
        $requestedName = 'TestRepository';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound($requestedName)->getMessage());

        $this->subject->__invoke($this->container, $requestedName);
    }

    public function testThrowsExceptionClassNotExtendsEntityRepository()
    {
        $requestedName = 'TestRepository';

        $this->getMockBuilder($requestedName)->getMock();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::doesNotExtend(EntityRepository::class)->getMessage());
        $this->subject->__invoke($this->container, $requestedName);
    }

    public function testCreateObjectThrowsExceptionAnnotationNotFound()
    {
        $repository = $this->createMock(EntityRepository::class);
        $this->annotationReader->method('getClassAnnotation')->willReturn(null);

        $this->subject->method('createAnnotationReader')->willReturn($this->annotationReader);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::annotationNotFound(
            Entity::class,
            $repository::class,
            get_class($this->subject)
        )->getMessage());

        $this->subject->__invoke($this->container, $repository::class);
    }

    public function testCreateObjectReturnsEntityRepository()
    {
        $repository    = $this->createMock(EntityRepository::class);
        $annotation    = new Entity('test');
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->method('getRepository')->willReturn($repository);

        $this->annotationReader->method('getClassAnnotation')->willReturn($annotation);

        $this->container->method('get')
            ->with(EntityManagerInterface::class)
            ->willReturn($entityManager);

        $this->subject
            ->method('createAnnotationReader')
            ->willReturn($this->annotationReader);

        $object = $this->subject->__invoke($this->container, $repository::class);

        $this->assertInstanceOf(EntityRepository::class, $object);
    }
}
