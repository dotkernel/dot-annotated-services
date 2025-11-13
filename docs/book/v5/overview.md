# Overview

`dot-annotated-services` is DotKernel's dependency injection service.

By providing reusable factories for service and repository injection, it reduces code complexity in projects.

> `dot-annotated-services` v5 has been abandoned in favor of [dot-dependency-injection](https://github.com/dotkernel/dot-dependency-injection), which uses PHP attributes instead of annotations.
>
> Going forward, if you want to use annotations in your projects, use [dot-annotated-services v4](../v4/overview.md), else use `dot-dependency-injection`.

## Version History

| Branch | PSR-11   | Docblock Comments | OSS Lifecycle                                                                                                                                           | PHP Version                                                                                                            |
|--------|----------|-------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------|
| 5.0    | 1 \|\| 2 | Attributes        | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F5.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/5.2.0) |
| 4.0    | 1        | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F4.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/4.4.0) |
| 3.0    | 1        | Annotations       | ![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F3.0%2FOSSMETADATA) | ![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/3.2.1) |

## Badges

![OSS Lifecycle](https://img.shields.io/osslifecycle?file_url=https%3A%2F%2Fgithub.com%2Fdotkernel%2Fdot-annotated-services%2Fblob%2F5.0%2FOSSMETADATA)
![PHP from Packagist (specify version)](https://img.shields.io/packagist/php-v/dotkernel/dot-annotated-services/5.2.0)

[![GitHub issues](https://img.shields.io/github/issues/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/issues)
[![GitHub forks](https://img.shields.io/github/forks/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/network)
[![GitHub stars](https://img.shields.io/github/stars/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/stargazers)
[![GitHub license](https://img.shields.io/github/license/dotkernel/dot-annotated-services)](https://github.com/dotkernel/dot-annotated-services/blob/5.0/LICENSE.md)

[![Build Static](https://github.com/dotkernel/dot-annotated-services/actions/workflows/continuous-integration.yml/badge.svg?branch=5.0)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/continuous-integration.yml)
[![codecov](https://codecov.io/gh/dotkernel/dot-annotated-services/graph/badge.svg?token=ZBZDEA3LY8)](https://codecov.io/gh/dotkernel/dot-annotated-services)
[![docs-build](https://github.com/dotkernel/dot-annotated-services/actions/workflows/docs-build.yml/badge.svg)](https://github.com/dotkernel/dot-annotated-services/actions/workflows/docs-build.yml)
