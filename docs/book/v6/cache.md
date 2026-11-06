# Caching the annotations

`dot-annotated-services` reads class annotations using [doctrine/annotations](https://github.com/doctrine/annotations) and can cache them in any [PSR-6](https://www.php-fig.org/psr/psr-6/) cache pool.

## Configuration

To cache annotations, register a service factory at key `Dot\AnnotatedServices\Factory\AbstractAnnotatedFactory::CACHE_SERVICE` (which is `Psr\Cache\CacheItemPoolInterface::class`).
It must return an implementation of `Psr\Cache\CacheItemPoolInterface`.

Without this service, annotations are parsed on every request.

If the `config` service has the key `debug` set to `true`, the cache checks whether the annotated files changed and refreshes stale entries.
Keep it `false` in production.

See below an example on how you can configure `dot-annotated-services` to cache annotations.
You can add this configuration value to your application's config file:

```php
'annotations_cache_dir' => __DIR__ . '/../../data/cache/annotations',
'dependencies' => [
    'factories' => [
        Dot\AnnotatedServices\Factory\AbstractAnnotatedFactory::CACHE_SERVICE => YourApp\Factory\AnnotationsCacheFactory::class,
    ],
],
```

where `AnnotationsCacheFactory` is a custom factory that needs to return a PSR-6 cache pool.
The example below uses [symfony/cache](https://github.com/symfony/cache), but any PSR-6 implementation works:

```php
<?php

declare(strict_types=1);

namespace YourApp\Factory;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class AnnotationsCacheFactory
{
    public function __invoke(ContainerInterface $container): CacheItemPoolInterface
    {
        return new FilesystemAdapter(
            namespace: 'annotations',
            directory: $container->get('config')['annotations_cache_dir'],
        );
    }
}
```

> Cache drivers from `doctrine/cache`, such as `FilesystemCache`, are not PSR-6 pools and cannot be used directly.
