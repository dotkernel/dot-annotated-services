<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Dot\AnnotatedServices\ConfigProvider;
use Dot\AnnotatedServices\Factory\AnnotatedServiceAbstractFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigProvider::class)]
class ConfigProviderTest extends TestCase
{
    /** @var array<string, mixed> */
    protected array $config;

    protected function setUp(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testHasDependencies(): void
    {
        $this->assertArrayHasKey('dependencies', $this->config);
    }

    public function testDependenciesHasFactories(): void
    {
        $this->assertIsArray($this->config['dependencies']);
        $this->assertArrayHasKey('abstract_factories', $this->config['dependencies']);
        $this->assertContainsEquals(
            AnnotatedServiceAbstractFactory::class,
            $this->config['dependencies']['abstract_factories']
        );
    }
}
