# Factories

`dot-annotated-services` is based on three reusable factories - `AnnotatedRepositoryFactory`, `AnnotatedServiceFactory` and `AnnotatedServiceAbstractFactory` - able to inject any dependency into a class.

## AnnotatedRepositoryFactory

Injects entity repositories into a class.

The repository must extend `Doctrine\ORM\EntityRepository` and carry the `@Entity` annotation.
The repository class returned by the entity manager must be the registered class: set the repository class on the entity (`#[ORM\Entity(repositoryClass: ...)]`) so that `EntityManager::getRepository()` returns an instance of it.

### Exceptions thrown

- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not exist
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not extend `Doctrine\ORM\EntityRepository`
- `Dot\AnnotatedServices\Exception\RuntimeException` if repository does not have `@Entity` annotation
- `Dot\AnnotatedServices\Exception\RuntimeException` if the entity manager returns a repository that is not an instance of the requested class
- `Psr\Container\NotFoundExceptionInterface` if `Doctrine\ORM\EntityManagerInterface` does not exist in the service container
- `Psr\Container\ContainerExceptionInterface` if service manager is unable to provide an instance of `Doctrine\ORM\EntityManagerInterface`

## AnnotatedServiceFactory

Injects class dependencies into classes.

A constructor with required parameters must carry the `@Inject` annotation.
A constructor without required parameters can omit it.

If a dependency is specified using the dot notation, `AnnotatedServiceFactory` will try to load a service having that specific alias.
If it does not find one, it will try to load the dependency as a config tree, checking each segment if it's available in the service container.

If a dependency is not registered in the service container but is the name of an existing class, `AnnotatedServiceFactory` creates it with `new`, without constructor arguments.

You can use the `@Inject` annotation on public setters too.
They are called, in declaration order, after the object is constructed and injected with the configured dependencies.
The constructor is only called once, and static methods are ignored.

### Exceptions thrown

- `Dot\AnnotatedServices\Exception\RuntimeException` if service does not exist
- `Dot\AnnotatedServices\Exception\RuntimeException` if service does not have `@Inject` annotation on its constructor, and the constructor has required parameters
- `Dot\AnnotatedServices\Exception\RuntimeException` if a dependency is neither a service nor a class
- `Dot\AnnotatedServices\Exception\InvalidArgumentException` if a key of a dotted dependency does not exist, including when the path is longer than the data
- `ReflectionException` on failure of creating a ReflectionClass of the dependency
- `Psr\Container\NotFoundExceptionInterface` if a dependency does not exist in the service container
- `Psr\Container\ContainerExceptionInterface` if service manager is unable to provide an instance of a dependency

## AnnotatedServiceAbstractFactory

Creates every class annotated with `@Service`, without any service manager configuration.
See [Create services without a factory](factories/abstract.md).

It never throws when asked whether it can create a class (for example through `$container->has()`): classes it cannot read, such as classes with unknown annotations, are reported as not creatable.
