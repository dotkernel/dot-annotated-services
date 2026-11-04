# Overview

`dot-annotated-services` is Dotkernel's dependency injection service using annotations.

By providing reusable factories for service and repository injection, it reduces code complexity in projects.

Version `6.0` continues the annotation-based API of version `4.x`.
Version `5.x` is a separate package line that uses PHP attributes instead of annotations.
See the [upgrade guide](upgrading.md) if you are coming from `4.x` or `5.x`.

## Requirements

- PHP `8.3`, `8.4`, `8.5` or `8.6`
- PSR-11 version `1` or `2`
- `laminas/laminas-servicemanager` `^3.22.1` or `^4.0`
- `doctrine/annotations` `^2.0`
- `doctrine/orm` `^3.0`

## Version History

| Branch | PSR-11   | Docblock Comments | OSS Lifecycle                                                                                                                                           | PHP Version                                                                                                            |
|--------|----------|-------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------|
| 6.0    | 1 \|\| 2 | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F6.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/6.0.0) |
| 5.0    | 1 \|\| 2 | Attributes        | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F5.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/5.2.0) |
| 4.0    | 1        | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F4.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/4.4.0) |
| 3.0    | 1        | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F3.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/3.2.1) |

Versions `4.x` and `6.0` are the proper releases of this package. They are annotation-based.

Version `5.x` is discontinued. It used PHP attributes, which are now provided by a separate package: [dot-dependency-injection](https://github.com/dotkernel/dot-dependency-injection) ([documentation](https://docs.dotkernel.org/dot-dependency-injection/)).
Use it if you want attributes instead of annotations. See [Version 5 is discontinued](../v5/overview.md) for migration notes.

## Badges

![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F6.0%2FOSSMETADATA)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/6.0.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/blob/6.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-annotated-services/actions/workflows/continuous-integration.yml/badge.svg?branch=6.0)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/continuous-integration.yml)
[![codecov](https://codecov.io/gh/dotkernel/dot-annotated-services/graph/badge.svg?token=ZBZDEA3LY8)](https://codecov.io/gh/dotkernel/dot-annotated-services)
[![PHPStan](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml/badge.svg?branch=6.0)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/static-analysis.yml)
