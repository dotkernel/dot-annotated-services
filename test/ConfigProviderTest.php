<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Dot\AnnotatedServices\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    protected array $config;

    protected function setup(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testConfigIsEmpty(): void
    {
        $this->assertEmpty($this->config);
    }
}
