# Configuration

After installation, register `dot-annotated-services` in your project by adding the below line to your configuration aggregator (usually: `config/config.php`):

```php
Dot\AnnotatedServices\ConfigProvider::class,
```

This registers `AnnotatedServiceAbstractFactory`, which creates every class marked with the `@Service` annotation without further configuration.
`AnnotatedServiceFactory` and `AnnotatedRepositoryFactory` are registered per class under `factories` in your own `ConfigProvider`.

See [Factories](factories.md) for details and [Cache](cache.md) to cache the parsed annotations.
