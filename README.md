# dot-annotated-services

DotKernel service creation component through zend-servicemanager and annotations

## Installation

Run the following command in your project directory
```bash
$ composer require dotkernel/dot-annotated-services
```

After installing, add `ConfigProvider` to your configuration.

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
use Dot\AnnotatedServices\Annotation\Service;
use Dot\AnnotatedServices\Annotation\Inject;

/**
 * Service constructor
 * @param Dependency1 $dep1
 * ...
 * 
 * @Inject({Dependency1::class, Dependency2::class, ...})
 */
public function __construct(Dependency1 $dep1, Dependency2 $dep2, ...)
{
    $this->dep1 = $dep1;
    //...
}
```

The annotation `@Inject` is telling the factory to inject the services between curly braces.
Valid service names should be provided, as registerd in the service manager.

To inject an array value from the service manager, you can use dot notation as below
```php
/**
 * @Inject({"config.debug"})
 */
```

which will inject `$container->get('config')['debug'];`

**Even if using dot notation, the annotated factory will check first if a service name exists with that name**

You can use the inject annotation on setters too, they will be called at creation time and injected with the configured dependencies.

### Using the abstract factory

Using this approach, no service manager configuration is required. It uses the registered abstract factory to create annotated services.

In order to tell the abstract factory which services are to be created, you need to annotate the service class with the `@Service` annotation.
```php
use Dot\AnnotatedServices\Annotation\Service;
use Dot\AnnotatedServices\Annotation\Inject;

/*
 * @Service
 */
class ServiceClass
{
    // configure injections as described in the previous section
}
```

And that's it, you don't need to configure the service manager with this class, creation will happen automatically.
