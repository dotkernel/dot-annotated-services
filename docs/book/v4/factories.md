# Factories

`dot-annotated-services` is based on 3 reusable factories - `AnnotatedRepositoryFactory`, `AnnotatedServiceFactory` and `AnnotatedServiceAbstractFactory` - able to inject any dependency into a class.

## AttributedRepositoryFactory

Injects entity repositories into a class.


### Exceptions thrown
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not exist
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not extend `Doctrine\ORM\EntityRepository`
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not have `@Entity` annotation
- `Psr\Container\NotFoundExceptionInterface` if `Doctrine\ORM\EntityManagerInterface` does not exist in the service container
- `Psr\Container\ContainerExceptionInterface` if service manager is unable to provide an instance of `Doctrine\ORM\EntityManagerInterface`


## AttributedServiceFactory

Injects class dependencies into classes.

If a dependency is specified using the dot notation, `AttributedServiceFactory` will try to load a service having that specific alias.
If it does not find one, it will try to load the dependency as a config tree, checking each segment if it's available in the service container.

You can use the inject annotation on setters too, they will be called at creation time and injected with the configured dependencies.


### Exceptions thrown
- `Dot\AnnotatedServices\Exception\RuntimeException` if service does not exist
- `Dot\AnnotatedServices\Exception\RuntimeException` if service does not have `@Inject` annotation on it's constructor
- `ReflectionException` on failure of creating a ReflectionClass of the dependency
- `Psr\Container\NotFoundExceptionInterface` if a dependency does not exist in the service container
- `Psr\Container\ContainerExceptionInterface` if service manager is unable to provide an instance of a dependency


## AnnotatedServiceAbstractFactory

Using this approach, no service manager configuration is required. It uses the registered abstract factory to create annotated services.

In order to tell the abstract factory which services are to be created, you need to annotate the service class with the `@Service` annotation.

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
And that's it, you don't need to configure the service manager with this class, creation will happen automatically.
