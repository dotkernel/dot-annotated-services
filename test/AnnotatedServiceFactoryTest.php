<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use ArrayObject;
use DomainException;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Exception\ExceptionInterface;
use Dot\AnnotatedServices\Exception\InvalidArgumentException;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AnnotatedServiceFactory;
use DotTest\AnnotatedServices\Fixture\ClassStringService;
use DotTest\AnnotatedServices\Fixture\ConfigService;
use DotTest\AnnotatedServices\Fixture\Dependency;
use DotTest\AnnotatedServices\Fixture\DottedConfigService;
use DotTest\AnnotatedServices\Fixture\InheritedSetterService;
use DotTest\AnnotatedServices\Fixture\MissingServiceService;
use DotTest\AnnotatedServices\Fixture\MissingSetterDependencyService;
use DotTest\AnnotatedServices\Fixture\MultiInjectService;
use DotTest\AnnotatedServices\Fixture\NoConstructorService;
use DotTest\AnnotatedServices\Fixture\NullKeyService;
use DotTest\AnnotatedServices\Fixture\OptionalParamsService;
use DotTest\AnnotatedServices\Fixture\RequiredParamsWithoutInject;
use DotTest\AnnotatedServices\Fixture\SetterEdgeCasesService;
use DotTest\AnnotatedServices\Fixture\SetterInjectedService;
use DotTest\AnnotatedServices\Fixture\ThrowingConstructorService;
use DotTest\AnnotatedServices\Fixture\UnbracedInjectService;
use DotTest\AnnotatedServices\Fixture\UnknownRootKeyService;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceManager;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(AnnotatedServiceFactory::class)]
class AnnotatedServiceFactoryTest extends TestCase
{
    private AnnotatedServiceFactory $subject;

    protected function setUp(): void
    {
        $this->subject                      = new AnnotatedServiceFactory();
        SetterInjectedService::$constructed = 0;
        SetterInjectedService::$staticCalls = 0;
        SetterInjectedService::$plainCalls  = 0;
    }

    /**
     * @param array<string, mixed> $services
     */
    private function container(array $services = []): ServiceManager
    {
        return new ServiceManager(['services' => $services]);
    }

    public function testThrowsExceptionClassNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound('TestService')->getMessage());

        ($this->subject)($this->container(), 'TestService');
    }

    public function testCreatesServiceWithoutConstructor(): void
    {
        $service = ($this->subject)($this->container(), NoConstructorService::class);

        $this->assertInstanceOf(NoConstructorService::class, $service);
    }

    public function testThrowsExceptionWhenInjectIsMissingAndConstructorRequiresParameters(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::annotationNotFound(
            Inject::class,
            RequiredParamsWithoutInject::class,
            AnnotatedServiceFactory::class
        )->getMessage());

        ($this->subject)($this->container(), RequiredParamsWithoutInject::class);
    }

    public function testCreatesServiceWithoutInjectWhenAllParametersAreOptional(): void
    {
        $service = ($this->subject)($this->container(), OptionalParamsService::class);

        $this->assertInstanceOf(OptionalParamsService::class, $service);
        $this->assertSame('default', $service->name);
    }

    public function testInjectsServiceFromContainer(): void
    {
        $config  = ['debug' => true];
        $service = ($this->subject)($this->container(['config' => $config]), ConfigService::class);

        $this->assertInstanceOf(ConfigService::class, $service);
        $this->assertSame($config, $service->config);
    }

    public function testInjectsServiceFromUnbracedAnnotation(): void
    {
        $config  = ['debug' => true];
        $service = ($this->subject)($this->container(['config' => $config]), UnbracedInjectService::class);

        $this->assertInstanceOf(UnbracedInjectService::class, $service);
        $this->assertSame($config, $service->config);
    }

    public function testInstantiatesClassStringWhenNotInContainer(): void
    {
        $service = ($this->subject)($this->container(), ClassStringService::class);

        $this->assertInstanceOf(ClassStringService::class, $service);
    }

    public function testPrefersContainerServiceOverClassInstantiation(): void
    {
        $dependency = new Dependency();
        $service    = ($this->subject)(
            $this->container([Dependency::class => $dependency]),
            ClassStringService::class
        );

        $this->assertInstanceOf(ClassStringService::class, $service);
        $this->assertSame($dependency, $service->dependency);
    }

    public function testInjectsNestedConfigValueUsingDotNotation(): void
    {
        $service = ($this->subject)(
            $this->container(['config' => ['a' => ['b' => 'value']]]),
            DottedConfigService::class
        );

        $this->assertInstanceOf(DottedConfigService::class, $service);
        $this->assertSame('value', $service->value);
    }

    public function testInjectsNullValueUsingDotNotation(): void
    {
        $service = ($this->subject)($this->container(['config' => ['k' => null]]), NullKeyService::class);

        $this->assertInstanceOf(NullKeyService::class, $service);
        $this->assertNull($service->value);
    }

    public function testServiceRegisteredWithDottedNameWinsOverConfigPath(): void
    {
        $service = ($this->subject)(
            $this->container([
                'config.a.b' => 'from-service',
                'config'     => ['a' => ['b' => 'from-config']],
            ]),
            DottedConfigService::class
        );

        $this->assertInstanceOf(DottedConfigService::class, $service);
        $this->assertSame('from-service', $service->value);
    }

    public function testThrowsExceptionWhenDottedKeyIsMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The key "b" provided in the dotted notation could not be found');

        ($this->subject)($this->container(['config' => ['a' => []]]), DottedConfigService::class);
    }

    public function testThrowsExceptionWhenDottedPathIsLongerThanTheData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The key "b" provided in the dotted notation could not be found');

        ($this->subject)($this->container(['config' => ['a' => 'scalar']]), DottedConfigService::class);
    }

    public function testThrowsExceptionWhenInjectedServiceDoesNotExist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound('missing_service')->getMessage());

        ($this->subject)($this->container(), MissingServiceService::class);
    }

    public function testCallsConstructorOnlyOnceAndInjectsSetters(): void
    {
        $config  = ['debug' => true];
        $service = ($this->subject)($this->container(['config' => $config]), SetterInjectedService::class);

        $this->assertInstanceOf(SetterInjectedService::class, $service);
        $this->assertSame(1, SetterInjectedService::$constructed);
        $this->assertSame($config, $service->config);
        $this->assertInstanceOf(Dependency::class, $service->dependency);
    }

    public function testDoesNotCallAnnotatedStaticMethods(): void
    {
        ($this->subject)($this->container(['config' => []]), SetterInjectedService::class);

        $this->assertSame(0, SetterInjectedService::$staticCalls);
    }

    public function testDoesNotCallPublicMethodsWithoutInject(): void
    {
        $service = ($this->subject)($this->container(['config' => []]), SetterInjectedService::class);

        $this->assertInstanceOf(SetterInjectedService::class, $service);
        $this->assertSame(0, SetterInjectedService::$plainCalls);
        $this->assertSame('value', $service->process('value'));
    }

    public function testInjectsValueFromArrayAccessObjectUsingDotNotation(): void
    {
        $config  = new ArrayObject(['a' => new ArrayObject(['b' => 'value'])]);
        $service = ($this->subject)($this->container(['config' => $config]), DottedConfigService::class);

        $this->assertInstanceOf(DottedConfigService::class, $service);
        $this->assertSame('value', $service->value);
    }

    public function testThrowsExceptionWhenKeyIsMissingFromArrayAccessObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The key "b" provided in the dotted notation could not be found');

        ($this->subject)(
            $this->container(['config' => ['a' => new ArrayObject([])]]),
            DottedConfigService::class
        );
    }

    public function testThrowsExceptionWhenDottedPathReachesAnObjectWithoutArrayAccess(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The key "b" provided in the dotted notation could not be found');

        ($this->subject)(
            $this->container(['config' => ['a' => new stdClass()]]),
            DottedConfigService::class
        );
    }

    public function testInjectsMixedServicesInTheDeclaredOrder(): void
    {
        $config  = ['debug' => true];
        $service = ($this->subject)(
            $this->container([
                'config'   => $config,
                'settings' => ['mode' => 'fast'],
            ]),
            MultiInjectService::class
        );

        $this->assertInstanceOf(MultiInjectService::class, $service);
        // Typed constructor parameters make a wrong injection order a TypeError, so the types prove the order
        $this->assertSame($config, $service->config);
        $this->assertSame('fast', $service->mode);
    }

    public function testThrowsExceptionWhenFirstSegmentOfDottedKeyIsNotAService(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound('unknown_root')->getMessage());

        ($this->subject)($this->container(), UnknownRootKeyService::class);
    }

    public function testInjectsSetterWithMultipleArguments(): void
    {
        $service = ($this->subject)(
            $this->container(['settings' => ['mode' => 'fast']]),
            SetterEdgeCasesService::class
        );

        $this->assertInstanceOf(SetterEdgeCasesService::class, $service);
        $this->assertInstanceOf(Dependency::class, $service->dependency);
        $this->assertSame('fast', $service->mode);
    }

    public function testDoesNotCallNonPublicMethodsWithInject(): void
    {
        $service = ($this->subject)(
            $this->container(['settings' => ['mode' => 'fast']]),
            SetterEdgeCasesService::class
        );

        $this->assertInstanceOf(SetterEdgeCasesService::class, $service);
        $this->assertFalse($service->protectedCalled);
    }

    public function testThrowsExceptionWhenSetterDependencyDoesNotExist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(RuntimeException::classNotFound('missing_setter_service')->getMessage());

        ($this->subject)($this->container(), MissingSetterDependencyService::class);
    }

    public function testInjectsSetterInheritedFromParentClass(): void
    {
        $service = ($this->subject)($this->container(), InheritedSetterService::class);

        $this->assertInstanceOf(InheritedSetterService::class, $service);
        $this->assertInstanceOf(Dependency::class, $service->dependency);
    }

    public function testContainerExceptionsAreNotWrapped(): void
    {
        $container = new ServiceManager([
            'factories' => [
                'config' => static function (): never {
                    throw new LogicException('boom');
                },
            ],
        ]);

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage('boom');

        ($this->subject)($container, ConfigService::class);
    }

    public function testConstructorExceptionsAreNotWrapped(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('constructor failed');

        ($this->subject)($this->container(), ThrowingConstructorService::class);
    }

    public function testThrownExceptionsImplementTheExceptionInterface(): void
    {
        try {
            ($this->subject)($this->container(), 'TestService');
            $this->fail('Exception expected');
        } catch (ExceptionInterface $exception) {
            $this->assertInstanceOf(RuntimeException::class, $exception);
        }
    }
}
