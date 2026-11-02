<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Annotations\Reader;
use Dot\AnnotatedServices\Factory\AbstractAnnotatedFactory;
use DotTest\AnnotatedServices\Fixture\ExposedAnnotatedFactory;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

#[CoversClass(AbstractAnnotatedFactory::class)]
class AbstractAnnotatedFactoryTest extends TestCase
{
    private ExposedAnnotatedFactory $subject;

    protected function setUp(): void
    {
        $this->subject = new ExposedAnnotatedFactory();
    }

    /**
     * @param array<string, mixed> $services
     */
    private function container(array $services = []): ServiceManager
    {
        return new ServiceManager(['services' => $services]);
    }

    public function testUsesPlainReaderWhenNoCacheIsRegistered(): void
    {
        $this->assertInstanceOf(AnnotationReader::class, $this->subject->reader($this->container()));
    }

    public function testUsesCachedReaderWhenACachePoolIsRegistered(): void
    {
        $container = $this->container([
            AbstractAnnotatedFactory::CACHE_SERVICE => $this->createStub(CacheItemPoolInterface::class),
        ]);

        $this->assertInstanceOf(PsrCachedReader::class, $this->subject->reader($container));
    }

    public function testUsesCachedReaderWhenCacheAndConfigAreRegistered(): void
    {
        $container = $this->container([
            AbstractAnnotatedFactory::CACHE_SERVICE => $this->createStub(CacheItemPoolInterface::class),
            'config'                                => ['debug' => true],
        ]);

        $this->assertInstanceOf(PsrCachedReader::class, $this->subject->reader($container));
    }

    public function testReusesTheReaderOnceCreated(): void
    {
        $container = $this->container();

        $this->assertSame($this->subject->reader($container), $this->subject->reader($container));
    }

    public function testExplicitlySetReaderTakesPrecedence(): void
    {
        $reader = $this->createStub(Reader::class);
        $this->subject->setAnnotationReader($reader);

        $this->assertSame($reader, $this->subject->reader($this->container()));
    }
}
