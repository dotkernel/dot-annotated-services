# Factories

`dot-annotated-services` is based on 2 reusable factories - `AttributedRepositoryFactory` and `AttributedServiceFactory` - able to inject any dependency into a class.

## AttributedRepositoryFactory

Injects entity repositories into a class.


### Exceptions thrown
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not exist
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not extend `Doctrine\ORM\EntityRepository`
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not have `#[Entity]` attribute
- `Psr\Container\NotFoundExceptionInterface` if `Doctrine\ORM\EntityManagerInterface` does not exist in the service container
- `Psr\Container\ContainerExceptionInterface` if service manager is unable to provide an instance of `Doctrine\ORM\EntityManagerInterface`


## AttributedServiceFactory

Injects class dependencies into classes.

If a dependency is specified using the dot notation, `AttributedServiceFactory` will try to load a service having that specific alias.
If it does not find one, it will try to load the dependency as a config tree, checking each segment if it's available in the service container.


### Exceptions thrown
- `Dot\AnnotatedServices\Exception\RuntimeException` if service does not exist
- `Dot\AnnotatedServices\Exception\RuntimeException` if service does not have `#[Inject]` attribute on it's constructor
- `Dot\AnnotatedServices\Exception\RuntimeException` if service tries to inject itself recursively
- `Psr\Container\NotFoundExceptionInterface` if a dependency does not exist in the service container
- `Psr\Container\ContainerExceptionInterface` if service manager is unable to provide an instance of a dependency
