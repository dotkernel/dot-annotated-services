<?php

namespace DotTest\AnnotatedServices;

use Doctrine\Common\Annotations\Reader;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AnnotatedServiceFactory as Subject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionMethod;
use ReflectionClass;

/**
 * Class AnnotatedServiceFactoryTest
 * @package DotTest\AnnotatedServices
 */
class AnnotatedServiceFactoryTest extends TestCase
{
    private ContainerInterface $container;

    private Subject $subject;

    private Reader $annotationReader;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = $this->createMock(ContainerInterface::class);
        $this->annotationReader = $this->createMock(Reader::class);
        $this->subject = $this->createPartialMock(Subject::class, [
            'createAnnotationReader',
            'getReflectionClass'
        ]);
    }

    public function testThrowsExceptionClassNotFound()
    {
        $requestedName = 'TestService';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound($requestedName)->getMessage());

        $this->subject->__invoke($this->container, $requestedName);
    }

    public function testReturnServiceWithNoDependencies()
    {
        $requestedName = 'TestService';
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

        $this->assertInstanceOf($requestedName, $object);
    }

    public function testThrowsExceptionAnnotationNotFound()
    {
        $requestedName = 'TestService';
        $this->getMockBuilder($requestedName)->allowMockingUnknownTypes()->getMock();
        $refClass = $this->createMock(ReflectionClass::class);
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

    public function testReturnService()
    {
        $requestedName = 'TestService';
        $this->getMockBuilder($requestedName)->allowMockingUnknownTypes()->getMock();
        $refClass = $this->createMock(ReflectionClass::class);
        $refConstructor = $this->createMock(ReflectionMethod::class);

        $refClass->method('getConstructor')->willReturn($refConstructor);
        $refClass->method('getMethods')->willReturn([]);
        $refConstructor->method('getNumberOfRequiredParameters')->willReturn(1);

        $inject = new Inject(['test']);
        $this->annotationReader->method('getMethodAnnotation')->willReturn($inject);

        $this->subject
            ->method('createAnnotationReader')
            ->willReturn($this->annotationReader);
        $this->subject->method('getReflectionClass')->willReturn($refClass);

        $service = $this->subject->__invoke($this->container, $requestedName);

        $this->assertInstanceOf($requestedName, $service);
    }
}

