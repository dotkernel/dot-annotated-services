<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AnnotatedRepositoryFactory as Subject;
//use DotTest\AnnotatedServices\TestClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

use function get_class;

class AnnotatedRepositoryFactoryTest extends TestCase
{
    private MockObject&ContainerInterface $container;
    private MockObject&Subject $subject;
    private MockObject&Reader $annotationReader;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->container        = $this->createMock(ContainerInterface::class);
        $this->annotationReader = $this->createMock(Reader::class);
        $this->subject          = $this->createPartialMock(Subject::class, ['createAnnotationReader']);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function testThrowsExceptionClassNotFound()
    {
        $requestedName = 'TestRepository';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound($requestedName)->getMessage());

        $this->subject->__invoke($this->container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function testThrowsExceptionClassNotExtendsEntityRepository()
    {
        $requestedName = TestClass::class;

        $this->getMockBuilder($requestedName)->getMock();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::doesNotExtend(EntityRepository::class)->getMessage());
        $this->subject->__invoke($this->container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws ReflectionException
     */
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

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
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

        $this->assertContainsOnlyInstancesOf(EntityRepository::class, [$object]);
    }
}
