# Create services without a factory

Using this approach, no service manager configuration is required.
The abstract factory registered by `Dot\AnnotatedServices\ConfigProvider` creates every class marked with the `@Service` annotation.

## Prepare class

Add the `@Service` annotation to the class docblock.
Configure the dependencies with `@Inject`, as described in [Inject class dependencies](service.md).

```php
<?php

declare(strict_types=1);

namespace YourApp\Service;

/**
 * @Dot\AnnotatedServices\Annotation\Service
 */
class Example
{
    /**
     * @Dot\AnnotatedServices\Annotation\Inject({
     *     YourApp\Repository\Dependency1::class,
     *     YourApp\Repository\Dependency2::class,
     *     "config.example"
     * })
     */
    public function __construct(
        protected YourApp\Repository\Dependency1 $dependency1,
        protected YourApp\Helper\Dependency2 $dependency2,
        protected array $exampleConfig,
    ) {
    }
}
```

> The annotation must be in a docblock, which starts with `/**`.
> A comment starting with `/*` is ignored, and the class is not created.

And that's it, you don't need to configure the service manager with this class, creation will happen automatically.

## Behavior

- Only class names are handled. Interfaces, unknown names and classes without `@Service` are left to the other factories of the container.
- Classes that contain annotations unknown to the annotation reader are reported as not creatable, instead of throwing an exception from `$container->has()`.
- Because the abstract factory is asked about every name the container cannot otherwise resolve, [cache the annotations](../cache.md) in production.
