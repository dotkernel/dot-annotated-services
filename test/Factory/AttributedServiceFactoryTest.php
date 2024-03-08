<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Factory;

use Dot\AnnotatedServices\Attribute\Inject;
use Dot\AnnotatedServices\Exception\InvalidArgumentException;
use Dot\AnnotatedServices\Exception\RuntimeException;
use Dot\AnnotatedServices\Factory\AttributedServiceFactory;
use DotTest\AnnotatedServices\TestData\RecursionService;
use DotTest\AnnotatedServices\TestData\ValidService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_key_exists;
use function sprintf;

class AttributedServiceFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionIfClassNotFound(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $subject = 'test';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(RuntimeException::MESSAGE_CLASS_NOT_FOUND, $subject)
        );

        (new AttributedServiceFactory())($container, $subject);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionIfAttributeNotFound(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $subject = new class {
            public function __construct()
            {
            }
        };

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(
                RuntimeException::MESSAGE_ATTRIBUTE_NOT_FOUND,
                Inject::class,
                $subject::class,
                AttributedServiceFactory::class
            )
        );

        (new AttributedServiceFactory())($container, $subject::class);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionOnRecursiveInjection(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $subject = new RecursionService();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(
                RuntimeException::MESSAGE_RECURSIVE_INJECT,
                $subject::class
            )
        );

        (new AttributedServiceFactory())($container, $subject::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionIfDottedServiceNotFound(): void
    {
        $mapping = [
            'config'  => [
                'uration' => [
                    'test' => [],
                ],
            ],
            'uration' => [
                'test' => [],
            ],
            'key'     => [],
        ];

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->any())->method('has')->willReturnCallback(
            function (string $key) use ($mapping): bool {
                return array_key_exists($key, $mapping);
            },
        );
        $container->expects($this->any())->method('get')->willReturnCallback(
            function (string $key) use ($mapping): array {
                return $mapping[$key] ?? [];
            },
        );

        $subject = new class
        {
            #[Inject('config.uration.key')]
            public function __construct(array $config = [])
            {
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(InvalidArgumentException::MESSAGE_MISSING_KEY, 'config.uration.key')
        );

        (new AttributedServiceFactory())($container, $subject::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function testWillThrowExceptionIfDependencyNotFound(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $subject = new class
        {
            #[Inject('test')]
            public function __construct(mixed $test = null)
            {
            }
        };

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(RuntimeException::MESSAGE_CLASS_NOT_FOUND, 'test')
        );

        (new AttributedServiceFactory())($container, $subject::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateServiceIfNoConstructor(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $subject = new class {
        };

        $service = (new AttributedServiceFactory())($container, $subject::class);
        $this->assertInstanceOf($subject::class, $service);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function testWillCreateService(): void
    {
        $mapping = [
            'config'  => [
                'uration' => [],
            ],
            'uration' => [],
        ];

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->any())->method('has')->willReturnCallback(
            function (string $key) use ($mapping): bool {
                return array_key_exists($key, $mapping);
            },
        );
        $container->expects($this->any())->method('get')->willReturnCallback(
            function (string $key) use ($mapping): array {
                return $mapping[$key] ?? [];
            },
        );

        $subject = new ValidService();

        $service = (new AttributedServiceFactory())($container, $subject::class);
        $this->assertInstanceOf(ValidService::class, $service);
    }
}
