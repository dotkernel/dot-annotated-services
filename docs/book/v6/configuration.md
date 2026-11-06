# Configuration

`dot-annotated-services` needs no global registration.
The factories are registered per class, under `factories` in your own `ConfigProvider`.
See [Inject class dependencies](factories/service.md) and [Inject entity repositories](factories/repository.md).

`Dot\AnnotatedServices\ConfigProvider` is still provided, but it returns an empty configuration.
You can keep it in your configuration aggregator (usually: `config/config.php`) if it is already there, or leave it out.

The factories only depend on `psr/container`, so they work with any PSR-11 container that accepts a factory callable per service key, such as Laminas Service Manager.

See [Factories](factories.md) for details and [Cache](cache.md) to cache the parsed annotations.
