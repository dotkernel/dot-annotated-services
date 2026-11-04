# Version 5 is discontinued

Version `5.x` of `dot-annotated-services` is discontinued and receives no further releases.
The last release was `5.2.0`.

Version `5.x` was a separate line of this package that replaced annotations with PHP attributes.
That functionality now lives in its own package, [dot-dependency-injection](https://github.com/dotkernel/dot-dependency-injection), which is the supported alternative for attribute-based dependency injection.

## Which package to use

| You want | Use |
| --- | --- |
| PHP attributes | [dot-dependency-injection](https://docs.dotkernel.org/dot-dependency-injection/) |
| Annotations (`doctrine/annotations`) | `dot-annotated-services` `6.0`, or `4.x` if you have to stay on Doctrine ORM `2.x` |

Versions `4.x` and `6.0` are the proper releases of `dot-annotated-services`.
They are annotation-based and are documented in [version 6](../v6/overview.md) and [version 4](../v4/overview.md).

## Migrating from 5.x to dot-dependency-injection

The attributes and factories keep their names and syntax.
Only the package and the namespaces change.

Replace the package:

```shell
composer remove dotkernel/dot-annotated-services
composer require dotkernel/dot-dependency-injection
```

Replace the namespaces in your code and configuration:

| 5.x (`dot-annotated-services`) | `dot-dependency-injection` |
| --- | --- |
| `Dot\AnnotatedServices\Attribute\Inject` | `Dot\DependencyInjection\Attribute\Inject` |
| `Dot\AnnotatedServices\Attribute\Entity` | `Dot\DependencyInjection\Attribute\Entity` |
| `Dot\AnnotatedServices\Factory\AttributedServiceFactory` | `Dot\DependencyInjection\Factory\AttributedServiceFactory` |
| `Dot\AnnotatedServices\Factory\AttributedRepositoryFactory` | `Dot\DependencyInjection\Factory\AttributedRepositoryFactory` |
| `Dot\AnnotatedServices\ConfigProvider` | `Dot\DependencyInjection\ConfigProvider` |

Check the requirements, including the supported PHP versions, in the [dot-dependency-injection documentation](https://docs.dotkernel.org/dot-dependency-injection/).

## Migrating from 5.x to annotations

If you prefer annotations, follow the [upgrade guide](../v6/upgrading.md#from-5x) for `6.0`.
