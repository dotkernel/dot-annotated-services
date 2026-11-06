# Inject class dependencies

## Prepare class

`dot-annotated-services` determines the dependencies by looking at the `@Inject` annotation, added to the constructor of a class.
Dependencies are specified as a list of service names or FQCNs.

```php
<?php

declare(strict_types=1);

namespace YourApp\Service;

class Example
{
    /**
     * @Dot\AnnotatedServices\Annotation\Inject({
     *     YourApp\Repository\Dependency1::class,
     *     YourApp\Repository\Dependency2::class,
     *     "config"
     * })
     */
    public function __construct(
        protected YourApp\Repository\Dependency1 $dependency1,
        protected YourApp\Helper\Dependency2 $dependency2,
        protected array $config
    ) {
    }
}
```

A single dependency can be written without the curly braces:

```php
/**
 * @Dot\AnnotatedServices\Annotation\Inject("config")
 */
public function __construct(protected array $config)
{
}
```

If your class needs the value of a specific configuration key, you can specify the path using dot notation:

```php
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
```

Rules for dot notation:

- The container is first asked for a service with the full name (`config.example`). Only if there is none is the name split at the dots.
- The first segment is the service (usually `config`), the following segments are array keys.
- A key whose value is `null` is injected as `null`.
- A key that does not exist, or a path longer than the data (for example `config.a.b` when `config['a']` is a string), throws `Dot\AnnotatedServices\Exception\InvalidArgumentException`.

## Setter injection

`@Inject` can also be added to public setters.
They are called after the constructor, and receive the configured dependencies as arguments:

```php
    /**
     * @Dot\AnnotatedServices\Annotation\Inject({YourApp\Helper\Dependency2::class})
     */
    public function setHelper(YourApp\Helper\Dependency2 $helper): void
    {
        $this->helper = $helper;
    }
```

The constructor is never called a second time, so classes with `readonly` promoted properties work.
Static methods are ignored.

## Register class

Open the ConfigProvider of the module where your class resides.

Add a new entry under `factories`, where the key is your class FQCN and the value is `Dot\AnnotatedServices\Factory\AnnotatedServiceFactory::class`.

See the below example for a better understanding of the file structure.

```php
<?php

declare(strict_types=1);

namespace YourApp;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                YourApp\Service\Example::class => Dot\AnnotatedServices\Factory\AnnotatedServiceFactory::class,
            ],
        ];
    }
}
```

> You can use only the fully qualified class name as the service key.
