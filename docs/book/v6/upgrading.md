# Upgrading to 6.0

Version `6.0` continues the annotation-based API of `4.x`.
The `5.x` releases are a separate, attribute-based package line.
Follow the section that matches the version you are coming from.

## From 4.x

### Requirements

- PHP `8.3` or newer is required. PHP `8.2` is no longer supported.
- `doctrine/cache` is no longer a requirement of this package. If your application uses its classes, require it yourself.
- `psr/cache` and `psr/container` are now declared as direct requirements.

### Changes that can break your code

| Change | Symptom | Fix |
| --- | --- | --- |
| The constructor is no longer called a second time when it carries `@Inject` | Code that relied on the constructor running twice no longer does | Remove that reliance. Classes with `readonly` promoted properties now work |
| `AnnotatedRepositoryFactory` throws if the entity manager returns a repository that is not an instance of the registered class | `Dot\AnnotatedServices\Exception\RuntimeException: Entity manager returned a repository of type ...` | Set `repositoryClass` on the entity to the registered repository, or register the class the entity manager returns |
| A dotted dependency whose path is longer than the data now throws | `InvalidArgumentException` where `config.a.b` used to inject the value of `config.a` | Fix the key path |
| `AnnotatedServiceAbstractFactory::canCreate()` returns `false` for classes it cannot read | `has()` returns `false` instead of throwing `AnnotationException` | None, unless your code caught that exception |
| `AnnotatedServiceFactory::__invoke()` and `createObject()` return `object` instead of `mixed` | Fatal error in subclasses that override them with an incompatible return type | Return an `object` from the override |
| `AbstractAnnotatedFactory::CACHE_SERVICE` is a typed constant (`string`) | Fatal error in subclasses that redeclare it untyped | Declare it as `public const string` |

### Behavior that now works

- `@Inject("config")` without curly braces is accepted.
- A dotted key whose value is `null` is injected as `null`. It used to throw.
- `has()` on the container no longer throws for classes with unknown annotations.

### Documentation changes

- The cache documentation of `4.x` described `doctrine/cache` drivers. The package has always required a PSR-6 cache pool. See [Cache](cache.md).
- `@Service` must be written in a docblock (`/**`), not in a `/*` comment.

## From 5.x

`5.x` uses PHP attributes. `6.0` uses annotations, and adds an abstract factory, setter injection and an annotation cache.
Migrating from `5.x` to `6.0` means moving back from attributes to annotations.

### Classes

| 5.x | 6.0 |
| --- | --- |
| `Dot\AnnotatedServices\Attribute\Inject` | `Dot\AnnotatedServices\Annotation\Inject` |
| `Dot\AnnotatedServices\Attribute\Entity` | `Dot\AnnotatedServices\Annotation\Entity` |
| `Dot\AnnotatedServices\Factory\AttributedServiceFactory` | `Dot\AnnotatedServices\Factory\AnnotatedServiceFactory` |
| `Dot\AnnotatedServices\Factory\AttributedRepositoryFactory` | `Dot\AnnotatedServices\Factory\AnnotatedRepositoryFactory` |

### Syntax

Before, on `5.x`:

```php
use Dot\AnnotatedServices\Attribute\Entity;
use Dot\AnnotatedServices\Attribute\Inject;

#[Entity(name: Example::class)]
class ExampleRepository extends EntityRepository
{
}

class ExampleService
{
    #[Inject(ExampleRepository::class, 'config')]
    public function __construct(
        protected ExampleRepository $repository,
        protected array $config,
    ) {
    }
}
```

After, on `6.0`:

```php
use Dot\AnnotatedServices\Annotation\Entity;
use Dot\AnnotatedServices\Annotation\Inject;

/**
 * @Entity(name="App\Entity\Example")
 */
class ExampleRepository extends EntityRepository
{
}

class ExampleService
{
    /**
     * @Inject({
     *     ExampleRepository::class,
     *     "config"
     * })
     */
    public function __construct(
        protected ExampleRepository $repository,
        protected array $config,
    ) {
    }
}
```

### Registration

Replace the factory class names in your `ConfigProvider`, and add `Dot\AnnotatedServices\ConfigProvider::class` to your configuration aggregator if you want to use `@Service`.

### Differences to be aware of

- `6.0` supports PSR-11 version `1` only. `5.x` also supported version `2`.
- `6.0` has no check for services that inject themselves. A service listed in its own `@Inject` is not reported as a recursion.
- `6.0` additionally supports `@Service` (see [Create services without a factory](factories/abstract.md)), `@Inject` on setters, and caching the parsed annotations (see [Cache](cache.md)).
- `6.0` requires `doctrine/annotations` and a PSR-6 pool if you want to cache annotations.
