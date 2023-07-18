<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Dot\AnnotatedServices\ConfigProvider;
use Dot\AnnotatedServices\Factory\AnnotatedServiceAbstractFactory;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    protected array $config;

    protected function setup(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testHasDependencies(): void
    {
        $this->assertArrayHasKey('dependencies', $this->config);
    }

    public function testDependenciesHasFactories(): void
    {
        $this->assertArrayHasKey('abstract_factories', $this->config['dependencies']);
        $this->assertContainsEquals(
            AnnotatedServiceAbstractFactory::class,
            $this->config['dependencies']['abstract_factories']
        );
    }
}
