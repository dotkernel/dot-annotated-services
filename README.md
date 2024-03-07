# dot-annotated-services

DotKernel dependency injection service.

This package can clean up your code, by getting rid of all the factories you write, sometimes just to inject a dependency or two.

![OSS Lifecycle](https://img.shields.io/osslifecycle/dotkernel/dot-annotated-services)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/5.0.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/blob/5.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml/badge.svg?branch=5.0)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml)
[![codecov](https://codecov.io/gh/dotkernel/dot-annotated-services/graph/badge.svg?token=ZBZDEA3LY8)](https://codecov.io/gh/dotkernel/dot-annotated-services)

[![SymfonyInsight](https://insight.symfony.com/projects/a0d7016e-fc3f-46b8-9b36-571ff060d744/big.svg)](https://insight.symfony.com/projects/a0d7016e-fc3f-46b8-9b36-571ff060d744)


## Installation

Install `dot-annotated-services` by running the following command in your project directory:

    composer require dotkernel/dot-annotated-services


After installing, register `dot-annotated-services` in your project by adding the below line to your configuration aggregate (usually: `config/config.php`):

     Dot\AnnotatedServices\ConfigProvider::class,


## Usage

### Using the AnnotatedServiceFactory

You can register services in the service manager using `AnnotatedServiceFactory` as seen in the below example:

```php
return [
    'factories' => [
        ServiceClass::class => AnnotatedServiceFactory::class,
    ],
];
```


### NOTE
> You can use only the fully qualified class name as the service key

The next step is to add the `#[Inject]` attribute to the service constructor with the service FQCNs to inject:

```php
use Dot\AnnotatedServices\Attribute\Inject;

#[Inject(
    Dependency1::class,
    Dependency2::class,
    "config",
)]
public function __construct(
    protected Dependency1 $dep1,
    protected Dependency2 $dep2,
    protected array $config
) {
}
```

The `#[Inject]` attribute is telling `AnnotatedServiceFactory` to inject the services specified as parameters.
Valid service names should be provided, as registered in the service manager.

To inject an array value from the service manager, you can use dot notation as below

```php
use Dot\AnnotatedServices\Attribute\Inject;

#[Inject(
    "config.debug",
)]
```
which will inject `$container->get('config')['debug'];`.


### NOTE 
> Even if using dot notation, `AnnotatedServiceFactory` will check first if a service name exists with that name.


### Using the AttributedRepositoryFactory 
You can register doctrine repositories and inject them using the `AttributedRepositoryFactory` as below:
```php
return [
    'factories' => [
        ExampleRepository::class => AttributedRepositoryFactory::class,
    ],
];
```

The next step is to add the `#[Entity]` attribute in the repository class.

The `name` field has to be the fully qualified class name.

Every repository should extend `Doctrine\ORM\EntityRepository`.
```php
use Api\App\Entity\Example;
use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Attribute\Entity;

#[Entity(name: Example::class)]
class ExampleRepository extends EntityRepository
{
}
```
