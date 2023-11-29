# dot-annotated-services

DotKernel component used to create services through [Laminas Service Manager](https://github.com/laminas/laminas-servicemanager) and inject them with dependencies just using method annotations. It can also create services without the need to write factories. Annotation parsing can be cached, to improve performance.

This package can clean up your code, by getting rid of all the factories you write, sometimes just to inject a dependency or two.

![OSS Lifecycle](https://img.shields.io/osslifecycle/dotkernel/dot-annotated-services)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/4.1.4)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/blob/4.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml/badge.svg?branch=4.0)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml)
[![codecov](https://codecov.io/gh/dotkernel/dot-annotated-services/graph/badge.svg?token=ZBZDEA3LY8)](https://codecov.io/gh/dotkernel/dot-annotated-services)

[![SymfonyInsight](https://insight.symfony.com/projects/a0d7016e-fc3f-46b8-9b36-571ff060d744/big.svg)](https://insight.symfony.com/projects/a0d7016e-fc3f-46b8-9b36-571ff060d744)


## Installation

Run the following command in your project directory

    composer require dotkernel/dot-annotated-services


After installing, add the `ConfigProvider` class to your configuration aggregate.

## Usage

### Using the AnnotatedServiceFactory

You can register services in the service manager using the `AnnotatedServiceFactory` as below
```php
return [
    'factories' => [
        ServiceClass::class => AnnotatedServiceFactory::class,
    ],
];
```

**Please note, you can use only the fully qualified class name as the service key**

The next step is to annotate the service constructor or setters with the service names to inject
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
Valid service names should be provided, as registered in the service manager.

To inject an array value from the service manager, you can use dot notation as below
```php
use Dot\AnnotatedServices\Annotation\Inject;

/**
 * @Inject({"config.debug"})
 */
```

which will inject `$container->get('config')['debug'];`

**Even if using dot notation, the annotated factory will check first if a service name exists with that name**

You can use the inject annotation on setters too, they will be called at creation time and injected with the configured dependencies.

### Using the AnnotatedRepositoryFactory 
You can register doctrine repositories and inject them using the AnnotatedRepositoryFactory as below:
```php
return [
    'factories' => [
        ExampleRepository::class => AnnotatedRepositoryFactory::class,
    ],
];
```

The next step is to add the `@Entity` annotation in the repository class.

The `name` field has to be the fully qualified class name.

Every repository should extend `Doctrine\ORM\EntityRepository`.
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


### Using the abstract factory

Using this approach, no service manager configuration is required. It uses the registered abstract factory to create annotated services.

In order to tell the abstract factory which services are to be created, you need to annotate the service class with the `@Service` annotation.
```php
use Dot\AnnotatedServices\Annotation\Service;

/*
 * @Service
 */
class ServiceClass
{
    // configure injections as described in the previous section
}
```

And that's it, you don't need to configure the service manager with this class, creation will happen automatically.


## Cache annotations

This package is built on top of `doctrine/annotation` and `doctrine/cache`.
In order to cache annotations, you should register a service factory at key `AbstractAnnotatedFactory::CACHE_SERVICE` that should return a valid `Doctrine\Common\Cache\Cache` cache driver. See [Cache Drivers](https://github.com/doctrine/cache/tree/master/lib/Doctrine/Common/Cache) for available implementations offered by doctrine.

Below, we give an example, as defined in our frontend and admin starter applications
```php
return [
    'annotations_cache_dir' => __DIR__ . '/../../data/cache/annotations',
    'dependencies' => [
        'factories' => [
            // used by dot-annotated-services to cache annotations
            // needs to return a cache instance from Doctrine\Common\Cache
            AbstractAnnotatedFactory::CACHE_SERVICE => AnnotationsCacheFactory::class,
        ]
    ],
];
```

```php
namespace Frontend\App\Factory;

use Doctrine\Common\Cache\FilesystemCache;
use Psr\Container\ContainerInterface;

class AnnotationsCacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        //change this to suite your caching needs
        return new FilesystemCache($container->get('config')['annotations_cache_dir']);
    }
}
```
