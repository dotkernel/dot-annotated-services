# dot-annotated-services

Dotkernel component used to create services through any PSR-11 container, such as [Laminas Service Manager](https://github.com/laminas/laminas-servicemanager), and inject them with dependencies just using method annotations.
Annotation parsing can be cached to improve performance.

This package can clean up your code by getting rid of all the factories you write, sometimes just to inject a dependency or two.

## Documentation

Documentation is available at: https://docs.dotkernel.org/dot-annotated-services/.

## Version History

| Branch | PSR-11   | Docblock Comments | OSS Lifecycle                                                                                                                                           | PHP Version                                                                                                            |
|--------|----------|-------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------|
| 6.0    | 1 \|\| 2 | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F6.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/6.0.0) |
| 5.0    | 1 \|\| 2 | Attributes        | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F5.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/5.2.0) |
| 4.0    | 1        | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F4.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/4.4.0) |
| 3.0    | 1        | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F3.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/3.2.1) |

Versions `4.x` and `6.0` are the proper releases of this package. They are annotation-based.

Version `5.x` is discontinued. It used PHP attributes, which are now provided by a separate package: [dot-dependency-injection](https://github.com/dotkernel/dot-dependency-injection) ([documentation](https://docs.dotkernel.org/dot-dependency-injection/)).
Use it if you want attributes instead of annotations. See [Version 5 is discontinued](https://github.com/dotkernel/dot-annotated-services/blob/6.0/docs/book/v5/overview.md) for migration notes.

## Badges

![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F6.0%2FOSSMETADATA)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/6.0.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/blob/6.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-annotated-services/actions/workflows/continuous-integration.yml/badge.svg?branch=6.0)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/continuous-integration.yml)
[![codecov](https://codecov.io/gh/dotkernel/dot-annotated-services/graph/badge.svg?token=ZBZDEA3LY8)](https://codecov.io/gh/dotkernel/dot-annotated-services)
[![PHPStan](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml/badge.svg?branch=6.0)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml)

## Installation

Run the following command in your project directory

```shell
composer require dotkernel/dot-annotated-services
```

## Usage

### Using the AnnotatedServiceFactory

You can register services in the container using the `AnnotatedServiceFactory` as below.

```php
return [
    'factories' => [
        ServiceClass::class => AnnotatedServiceFactory::class,
    ],
];
```

> You can use only the fully qualified class name as the service key.

The next step is to annotate the service constructor or setters with the service names to inject.

```php
use Dot\AnnotatedServices\Annotation\Inject;

/**
 * @Inject({
 *     Dependency1::class,
 *     Dependency2::class,
 *     "config"
 * })
 */
public function __construct(
    protected Dependency1 $dep1,
    protected Dependency2 $dep2,
    protected array $config
) {
}
```

The annotation `@Inject` is telling the factory to inject the services between curly braces.
Valid service names should be provided, as registered in the container.

To inject an array value from the container, you can use dot notation as below.

```php
use Dot\AnnotatedServices\Annotation\Inject;

/**
 * @Inject({"config.debug"})
 */
```

which will inject `$container->get('config')['debug'];`

> Even if using dot annotation, the annotated factory will check first if a service name exists with that name.

You can use the `@Inject` annotation on setters too, they will be called at creation time and injected with the configured dependencies.

### Using the AnnotatedRepositoryFactory

You can register doctrine repositories and inject them using the AnnotatedRepositoryFactory as below.

```php
return [
    'factories' => [
        ExampleRepository::class => AnnotatedRepositoryFactory::class,
    ],
];
```

The next step is to add the `@Entity` annotation in the repository class.

The `name` field has to be the fully qualified class name.

Every repository should extend `Doctrine\ORM\EntityRepository`, and must be the repository class of the entity.

```php
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;

/**
 * @Entity(name="App\Entity\Example")
 */
class ExampleRepository extends EntityRepository
{
}
```

## Cache annotations

This package is built on top of `doctrine/annotations`.
To cache annotations, you should register a service factory at key `AbstractAnnotatedFactory::CACHE_SERVICE` that should return a valid PSR-6 cache pool, an implementation of `Psr\Cache\CacheItemPoolInterface`.

Below, we give an example, using [symfony/cache](https://github.com/symfony/cache):

```php
return [
    'annotations_cache_dir' => __DIR__ . '/../../data/cache/annotations',
    'dependencies' => [
        'factories' => [
            // used by dot-annotated-services to cache annotations
            // needs to return a PSR-6 cache pool
            AbstractAnnotatedFactory::CACHE_SERVICE => AnnotationsCacheFactory::class,
        ]
    ],
];
```

```php
namespace Frontend\App\Factory;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class AnnotationsCacheFactory
{
    public function __invoke(ContainerInterface $container): CacheItemPoolInterface
    {
        //change this to suite your caching needs
        return new FilesystemAdapter(
            namespace: 'annotations',
            directory: $container->get('config')['annotations_cache_dir'],
        );
    }
}
```
