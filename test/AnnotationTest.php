<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices;

use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\AnnotatedServices\Exception\ExceptionInterface;
use Dot\AnnotatedServices\Exception\InvalidArgumentException;
use Dot\AnnotatedServices\Exception\RuntimeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function class_implements;

#[CoversClass(Inject::class)]
#[CoversClass(Entity::class)]
#[CoversClass(RuntimeException::class)]
#[CoversClass(InvalidArgumentException::class)]
class AnnotationTest extends TestCase
{
    public function testInjectKeepsListOfServices(): void
    {
        $inject = new Inject(['value' => ['a', 'b']]);

        $this->assertSame(['a', 'b'], $inject->getServices());
    }

    public function testInjectAcceptsASingleUnbracedService(): void
    {
        $inject = new Inject(['value' => 'config']);

        $this->assertSame(['config'], $inject->getServices());
    }

    public function testInjectWithoutValueHasNoServices(): void
    {
        $this->assertSame([], (new Inject([]))->getServices());
    }

    public function testEntityExposesItsName(): void
    {
        $this->assertSame(self::class, (new Entity(self::class))->getName());
    }

    public function testExceptionsImplementTheExceptionInterface(): void
    {
        $this->assertContains(ExceptionInterface::class, class_implements(RuntimeException::class));
        $this->assertContains(ExceptionInterface::class, class_implements(InvalidArgumentException::class));
    }

    public function testRepositoryMismatchMessageNamesBothClasses(): void
    {
        $message = RuntimeException::repositoryMismatch('App\Repo', EntityRepository::class)->getMessage();

        $this->assertStringContainsString('App\Repo', $message);
        $this->assertStringContainsString(EntityRepository::class, $message);
    }

    public function testInvalidAnnotationMessageNamesTheService(): void
    {
        $this->assertStringContainsString('"Foo"', RuntimeException::invalidAnnotation('Foo')->getMessage());
    }
}
