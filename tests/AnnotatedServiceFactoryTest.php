<?php


namespace DotTest\AnnotatedServices;


use Doctrine\Common\Annotations\Reader;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AnnotatedServiceFactory as Subject;
use DotTest\AnnotatedServices\Stubs\TestService;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

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
        $this->subject = $this->createPartialMock(Subject::class, ['createAnnotationReader']);
    }

    public function testThrowsExceptionClassNotFound()
    {
        $requestedName = 'TestService';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound($requestedName)->getMessage());

        $this->subject->__invoke($this->container, $requestedName);
    }

    public function testReturnsSimpleService()
    {
        $requestedName = TestService::class;
        $object = $this->subject->__invoke($this->container, $requestedName);

        $this->assertInstanceOf($requestedName, $object);
    }
}
