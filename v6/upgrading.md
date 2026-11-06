# Upgrading to 6.0

Version `6.0` continues the annotation-based API of `4.x`.
The `5.x` releases are a separate, attribute-based package line.
Follow the section that matches the version you are coming from.

## From 4.x

### Requirements

- PHP `8.3` or newer is required. PHP `8.2` is no longer supported.
- `doctrine/orm` `^3.0` and `doctrine/annotations` `^2.0` are required. Applications still on Doctrine ORM `2.x` or `doctrine/annotations` `1.x` must upgrade those first, or stay on `4.x`.
- `doctrine/cache` is no longer a requirement of this package, and `doctrine/orm` `3.x` does not install it. If your application uses its classes, require it yourself.
- `laminas/laminas-servicemanager` is no longer a requirement. The factories only depend on `psr/container`, version `1.1` or `2`. If your application uses Laminas Service Manager, require it yourself.
- `psr/cache` and `psr/container` are now declared as direct requirements.

### Changes that can break your code

| Change | Symptom | Fix |
| --- | --- | --- |
| `AnnotatedServiceAbstractFactory` and the `@Service` annotation are removed | Class not found, or the service is no longer created automatically | Register the class under `factories` with `AnnotatedServiceFactory`, and remove `@Service` and the abstract factory registration. See below |
| `Dot\AnnotatedServices\ConfigProvider` no longer registers the abstract factory | `abstract_factories` is no longer filled by this package | None, unless you read that configuration key |
| The constructor is no longer called a second time when it carries `@Inject` | Code that relied on the constructor running twice no longer does | Remove that reliance. Classes with `readonly` promoted properties now work |
| `AnnotatedRepositoryFactory` throws if the entity manager returns a repository that is not an instance of the registered class | `Dot\AnnotatedServices\Exception\RuntimeException: Entity manager returned a repository of type ...` | Set `repositoryClass` on the entity to the registered repository, or register the class the entity manager returns |
| A dotted dependency whose path is longer than the data now throws | `InvalidArgumentException` where `config.a.b` used to inject the value of `config.a` | Fix the key path |
| `AnnotatedServiceFactory::__invoke()` and `createObject()` return `object` instead of `mixed` | Fatal error in subclasses that override them with an incompatible return type | Return an `object` from the override |
| `AbstractAnnotatedFactory::CACHE_SERVICE` is a typed constant (`string`) | Fatal error in subclasses that redeclare it untyped | Declare it as `public const string` |

### Behavior that now works

- `@Inject("config")` without curly braces is accepted.
- A dotted key whose value is `null` is injected as `null`. It used to throw.

### Replacing `@Service`

Before, on `4.x`:

```php
/**
 * @Service
 */
class ExampleService
{
}
```

After, on `6.0`: remove the annotation and register the class with the factory in your `ConfigProvider`:

```php
'dependencies' => [
    'factories' => [
        ExampleService::class => Dot\AnnotatedServices\Factory\AnnotatedServiceFactory::class,
    ],
],
```

Also remove `Dot\AnnotatedServices\Factory\AnnotatedServiceAbstractFactory` from `abstract_factories` if you registered it yourself.

### Documentation changes

- The cache documentation of `4.x` described `doctrine/cache` drivers. The package has always required a PSR-6 cache pool. See [Cache](cache.md).

## From 5.x

`5.x` uses PHP attributes. `6.0` uses annotations, and adds setter injection and an annotation cache.
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

Replace the factory class names in your `ConfigProvider`. Nothing needs to be added to your configuration aggregator.

### Differences to be aware of

- `6.0` requires Doctrine ORM `3.x`. `5.x` also supported `2.x`.
- `6.0` has no check for services that inject themselves. A service listed in its own `@Inject` is not reported as a recursion.
- `6.0` additionally supports `@Inject` on setters, and caching the parsed annotations (see [Cache](cache.md)).
- `6.0` requires `doctrine/annotations` and a PSR-6 pool if you want to cache annotations.
