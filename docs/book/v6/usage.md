# Usage

Version `6.x` of `dot-annotated-services` uses [doctrine/annotations](https://github.com/doctrine/annotations) to parse and inject dependencies.
It continues the annotation-based API of version `4.x`.
Version `5.x` is a separate package line that uses PHP attributes instead.

> Annotations must be written in a docblock, which starts with `/**`.
> A comment starting with `/*` is ignored.

You can use it to:

- [Inject class dependencies](factories/service.md)
- [Inject entity repositories](factories/repository.md)
- [Create services without writing a factory](factories/abstract.md)

Coming from `4.x` or `5.x`? Read the [upgrade guide](upgrading.md).
