<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Dot\AnnotatedServices\ConfigProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigProvider::class)]
class ConfigProviderTest extends TestCase
{
    public function testRegistersNothingGlobally(): void
    {
        $this->assertSame([], (new ConfigProvider())());
    }
}
