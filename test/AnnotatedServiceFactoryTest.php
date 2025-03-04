<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Doctrine\Common\Annotations\Reader;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AnnotatedServiceFactory as Subject;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

use function get_class;

class AnnotatedServiceFactoryTest extends TestCase
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
        $this->subject          = $this->createPartialMock(Subject::class, [
            'createAnnotationReader',
            'getReflectionClass',
        ]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function testThrowsExceptionClassNotFound()
    {
        $requestedName = 'TestService';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound($requestedName)->getMessage());

        $this->subject->__invoke($this->container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function testReturnServiceWithNoDependencies()
    {
        $requestedName = TestClass::class;
        $this->getMockBuilder($requestedName)->allowMockingUnknownTypes()->getMock();
        $refClass = $this->createMock(ReflectionClass::class);

        $refClass->method('getConstructor')->willReturn(null);
        $refClass->method('getMethods')->willReturn([]);

        $this->annotationReader->method('getMethodAnnotation')->willReturn(null);
        $this->subject
            ->method('createAnnotationReader')
            ->willReturn($this->annotationReader);
        $this->subject->method('getReflectionClass')->willReturn($refClass);

        $object = $this->subject->__invoke($this->container, $requestedName);

        $this->assertSame($requestedName, $object::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function testThrowsExceptionAnnotationNotFound()
    {
        $requestedName = TestClass::class;
        $this->getMockBuilder($requestedName)->allowMockingUnknownTypes()->getMock();
        $refClass       = $this->createMock(ReflectionClass::class);
        $refConstructor = $this->createMock(ReflectionMethod::class);

        $refClass->method('getConstructor')->willReturn($refConstructor);
        $refConstructor->method('getNumberOfRequiredParameters')->willReturn(100);

        $this->annotationReader->method('getMethodAnnotation')->willReturn(null);
        $this->subject
            ->method('createAnnotationReader')
            ->willReturn($this->annotationReader);
        $this->subject->method('getReflectionClass')->willReturn($refClass);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::annotationNotFound(
            Inject::class,
            $requestedName,
            get_class($this->subject),
        )->getMessage());

        $this->subject->__invoke($this->container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function testReturnService()
    {
        $requestedName = TestClass::class;
        $this->getMockBuilder($requestedName)->allowMockingUnknownTypes()->getMock();
        $refClass       = $this->createMock(ReflectionClass::class);
        $refConstructor = $this->createMock(ReflectionMethod::class);

        $refClass->method('getConstructor')->willReturn($refConstructor);
        $refClass->method('getMethods')->willReturn([]);
        $refConstructor->method('getNumberOfRequiredParameters')->willReturn(1);

        $inject = new Inject(['test']);
        $this->annotationReader->method('getMethodAnnotation')->willReturn($inject);

        $this->subject->method('createAnnotationReader')->willReturn($this->annotationReader);
        $this->subject->method('getReflectionClass')->willReturn($refClass);

        $service = $this->subject->__invoke($this->container, $requestedName);

        $this->assertSame($requestedName, $service::class);
    }
}
