# Caching the annotations

`dot-annotated-services` reads class annotations using [doctrine/annotations](https://github.com/doctrine/annotations) and caches them using [doctrine/cache](https://github.com/doctrine/cache).


## Configuration

In order to cache annotations, you should register a service factory at key `AbstractAnnotatedFactory::CACHE_SERVICE` that should return a valid `Doctrine\Common\Cache\Cache` cache driver.
See [Cache Drivers](https://github.com/doctrine/cache/tree/1.13.x/lib/Doctrine/Common/Cache) for available implementations offered by doctrine.

See below an example on how you can configure `dot-annotated-services` to cache annotations.
You can add this configuration values to your application's Doctrine config file:

```php
    'annotations_cache_dir' => __DIR__ . '/../../data/cache/annotations',
    'dependencies' => [
        'factories' => [
            Dot\AnnotatedServices\Factory\AbstractAnnotatedFactory::CACHE_SERVICE => YourApp\Factory\AnnotationsCacheFactory::class,
        ],
    ];
```
where `AnnotationsCacheFactory` is a custom factory that needs to return a [Doctrine Cache Driver](https://github.com/doctrine/cache/tree/1.13.x/lib/Doctrine/Common/Cache):
```php
<?php

declare(strict_types=1);

namespace YourApp\Factory;

class AnnotationsCacheFactory
{
    public function __invoke(Psr\Container\ContainerInterface $container)
    {
        return new Doctrine\Common\Cache\FilesystemCache($container->get('config')['annotations_cache_dir']);
    }
}
```
