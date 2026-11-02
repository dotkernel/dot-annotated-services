<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Dot\AnnotatedServices\ConfigProvider;
use Dot\AnnotatedServices\Factory\AnnotatedServiceAbstractFactory;
use DotTest\AnnotatedServices\Fixture\AnnotatedService;
use DotTest\AnnotatedServices\Fixture\CommentOnlyService;
use DotTest\AnnotatedServices\Fixture\PlainClass;
use DotTest\AnnotatedServices\Fixture\ServiceInterface;
use DotTest\AnnotatedServices\Fixture\UnknownAnnotationClass;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AnnotatedServiceAbstractFactory::class)]
class AnnotatedServiceAbstractFactoryTest extends TestCase
{
    private AnnotatedServiceAbstractFactory $subject;

    protected function setUp(): void
    {
        $this->subject = new AnnotatedServiceAbstractFactory();
    }

    /**
     * @param array<string, mixed> $services
     */
    private function container(array $services = []): ServiceManager
    {
        $config                       = (new ConfigProvider())()['dependencies'];
        $config['services']           = $services;
        $config['abstract_factories'] = [AnnotatedServiceAbstractFactory::class];

        return new ServiceManager($config);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function notCreatableProvider(): array
    {
        return [
            'class without @Service'           => [PlainClass::class],
            'interface'                        => [ServiceInterface::class],
            'unknown name'                     => ['This\Class\Does\Not\Exist'],
            '/* */ comment is not a docblock'  => [CommentOnlyService::class],
            'docblock with unknown annotation' => [UnknownAnnotationClass::class],
        ];
    }

    public function testCanCreateClassAnnotatedWithService(): void
    {
        $this->assertTrue($this->subject->canCreate($this->container(), AnnotatedService::class));
    }

    #[DataProvider('notCreatableProvider')]
    public function testCannotCreateOtherNames(string $requestedName): void
    {
        $this->assertFalse($this->subject->canCreate($this->container(), $requestedName));
    }

    #[DataProvider('notCreatableProvider')]
    public function testContainerReportsOtherNamesAsMissingWithoutThrowing(string $requestedName): void
    {
        $this->assertFalse($this->container()->has($requestedName));
    }

    public function testCreatesAnnotatedServiceThroughTheContainer(): void
    {
        $config    = ['debug' => true];
        $container = $this->container(['config' => $config]);

        $this->assertTrue($container->has(AnnotatedService::class));

        $service = $container->get(AnnotatedService::class);

        $this->assertInstanceOf(AnnotatedService::class, $service);
        $this->assertSame($config, $service->config);
    }

    public function testInvokeBuildsService(): void
    {
        $config  = ['debug' => false];
        $service = ($this->subject)($this->container(['config' => $config]), AnnotatedService::class);

        $this->assertInstanceOf(AnnotatedService::class, $service);
        $this->assertSame($config, $service->config);
    }
}
