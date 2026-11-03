# Changelog

All notable changes to this project are documented in this file.

Version `5.x` is a separate, attribute-based line of this package (see `5.0.0`).
Version `6.0.0` continues the annotation-based API of `4.x`.

## 6.0.0 - Unreleased

### Changed

- Minimum PHP version is now 8.3. Supported versions: 8.3, 8.4, 8.5 and 8.6.
- `doctrine/orm` is now required as `^3.0` (was `^2.20`) and `doctrine/annotations` as `^2.0` (was `^1.14.3`).
- `laminas/laminas-servicemanager` constraint widened from `^3.22.1` to `^3.22.1 || ^4.0`, and PSR-11 version 2 is supported (`psr/container` `^1.1 || ^2.0`).
- `AnnotatedRepositoryFactory` now throws a `RuntimeException` when the entity manager returns a repository that is not an instance of the requested class. Set `repositoryClass` on the entity to the registered repository.
- `AnnotatedServiceFactory::__invoke()` and `AnnotatedServiceFactory::createObject()` now return `object` instead of `mixed`.
- `AbstractAnnotatedFactory::CACHE_SERVICE` is now a typed constant (`public const string`).
- `Inject` accepts a single unbraced service, e.g. `@Inject("config")`.
- `Entity::getName()` is typed as `class-string`.
- `AnnotatedServiceAbstractFactory::canCreate()` returns `false` for classes whose annotations cannot be read, and the abstract factory reuses a single `AnnotatedServiceFactory`.
- Array and generic types are specified in docblocks, so the code passes PHPStan level 8.
- Development tooling: PHPUnit 12, PHPStan level 8, GitHub Actions run PHP 8.3 to 8.6 with updated `actions/checkout`, `actions/cache` and `codecov/codecov-action`, and the Laminas backward-compatibility check is disabled for this major version.
- Documentation for 6.0 is available in `docs/book/v6`, including an upgrade guide for 4.x and 5.x, and a page for the abstract factory.
- The cache documentation describes a PSR-6 cache pool (`Psr\Cache\CacheItemPoolInterface`) instead of `doctrine/cache` drivers.
- The README and docs show the `@Service` annotation in a docblock (`/**`).
- Temporary: `phpunit.xml` sets `ignoreIndirectDeprecations="true"` on `<source>`, because `laminas/laminas-servicemanager` calls `spl_object_hash()`, which is deprecated in PHP 8.6. Remove it once a fixed release of `laminas/laminas-servicemanager` is available.

### Added

- `RuntimeException::repositoryMismatch()`.
- `psr/cache` and `psr/container` as direct requirements, and a `suggest` entry for a PSR-6 cache implementation.
- A test suite with fixtures, a real annotation reader and a real service manager, covering the factories, the abstract factory, the annotation reader selection, the annotations and the exceptions.

### Deprecated

- Nothing

### Removed

- PHP 8.2 support.
- `doctrine/cache` as a requirement. `doctrine/orm` 3.x does not install it either, so your own code should require it if it uses its classes.
- Support for Doctrine ORM 2.x and `doctrine/annotations` 1.x.

### Fixed

- The constructor of a service was called a second time while injecting setters. Services with `readonly` promoted properties could not be created.
- A dotted dependency such as `config.key` failed when the value was `null`. It is now injected as `null`.
- A dotted dependency longer than the data (e.g. `config.a.b` where `config['a']` is a string) returned an intermediate value. It now throws an `InvalidArgumentException`.
- `@Inject("config")` without curly braces failed with a creation error.
- `has()` and `get()` on the container threw an `AnnotationException` for unrelated classes that contain annotations unknown to the annotation reader, when the abstract factory was registered.
- Static methods annotated with `@Inject` are no longer called during setter injection.

## 5.2.0 - 2024-10-23

### Changed

- Version 5 entered maintenance mode (Issue #52).
- `laminas/laminas-coding-standard` updated to `^3.0`.
- Code linting.
- `SECURITY.md` targets the latest releases, and the site description in `mkdocs.yml` was updated.

### Added

- PHP 8.4 support.
- `.laminas-ci.json`.

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 5.1.2 - 2024-05-03

### Changed

- Documentation build workflow and `mkdocs.yml`.

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 5.1.1 - 2024-04-24

### Changed

- Documentation build workflow, `mkdocs.yml` and `README.md`.

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 5.1.0 - 2024-04-24

### Changed

- Continuous integration uses the GitHub workflows provided by Laminas.
- `doctrine/orm` constraint changed from `^2.0 || ^3.0` to `^2.9 || ^3.0`.
- Code linting.

### Added

- Documentation for versions 4 and 5 (Issue #37), and the documentation build GitHub Action.

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- The `Build static` badge in `README.md`.

## 5.0.0 - 2024-03-08

### Changed

- Annotations were replaced with PHP attributes (Issue #32): `Dot\AnnotatedServices\Attribute\Inject`, `Dot\AnnotatedServices\Attribute\Entity`, `AttributedServiceFactory` and `AttributedRepositoryFactory`.
- The package no longer depends on `doctrine/annotations`, `doctrine/cache` and `laminas/laminas-servicemanager`.
- `Dot\AnnotatedServices\ConfigProvider` no longer registers an abstract factory.

### Added

- `psr/container` `^1.0 || ^2.0` as a requirement.
- `SECURITY.md`.

### Deprecated

- Nothing

### Removed

- PHP 8.1 support.
- `AnnotatedServiceAbstractFactory` and the `@Service` annotation.
- Annotation caching, and `@Inject` on setters.

### Fixed

- Nothing

## 4.4.0 - 2025-11-03

### Changed

- Updated the PHP versions in the continuous integration workflows (Issue #60).
- Updated README, `SECURITY.md` and documentation.

### Added

- PHP 8.5 support.

### Deprecated

- Nothing

### Removed

- The version 5 documentation, which belongs to the `5.0` branch.

### Fixed

- Nothing

## 4.3.0 - 2025-03-04

### Changed

- Replaced Psalm with PHPStan for static analysis (Issue #58).

### Added

- `phpstan/phpstan` and `phpstan/phpstan-phpunit`, `phpstan.neon` and a static analysis GitHub workflow.
- Unit tests for `AnnotatedRepositoryFactory` and `AnnotatedServiceFactory` were extended.

### Deprecated

- Nothing

### Removed

- `vimeo/psalm` and `psalm.xml`.

### Fixed

- Nothing

## 4.2.1 - 2025-01-27

### Changed

- Psalm updated to 6.x (Issue #56).
- Codecov runs on PHP 8.4 as well.
- Updated README and documentation, and reordered the packages in `composer.json`.

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 4.2.0 - 2024-10-23

### Changed

- Version 4 entered active mode (Issue #52).
- `doctrine/orm` constraint changed from `^2.17.3` to `^2.20`.
- `laminas/laminas-coding-standard` updated to `^3.0`.
- Code linting.

### Added

- PHP 8.4 support.
- Documentation for version 4 (Issue #37), and the documentation build GitHub Action.

### Deprecated

- Nothing

### Removed

- PHP 8.1 support.

### Fixed

- Nothing

## 4.1.7 - 2024-02-23

### Changed

- Updated dependencies: `laminas/laminas-servicemanager` `^3.22.1`, `doctrine/annotations` `^1.14.3`, `doctrine/cache` `^1.12.1 || ^2.1.1`, `doctrine/orm` `^2.17.3`, `phpunit/phpunit` `^10.5.9` and `vimeo/psalm` `^5.20`.
- `doctrine/cache` versions in `composer.json` widened to stay backward compatible.
- Annotations are read with `Doctrine\Common\Annotations\PsrCachedReader`. `AbstractAnnotatedFactory::CACHE_SERVICE` is now `Psr\Cache\CacheItemPoolInterface::class`, and the service registered at that key must be a PSR-6 cache pool.

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- The call to `AnnotationRegistry::registerLoader()`.

### Fixed

- Deprecations of `doctrine/annotations` v2.

## 4.1.4 - 2023-11-29

### Changed

- README update.

### Added

- PHP 8.3 support.

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 4.1.3 - 2023-08-29

### Changed

- README update.

### Added

- Codecov integration (Issue #21), and the Codecov and static analysis badges.

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 4.1.2 - 2023-07-25

### Changed

- Nothing

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- `AbstractAnnotatedFactory::CACHE_SERVICE` was restored to public.

## 4.1.1 - 2023-07-20

### Changed

- Nothing

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- The `annotationReader` property of `AbstractAnnotatedFactory` is initialized to `null`.

## 4.1.0 - 2023-07-18

### Changed

- Minimum PHP version is now 8.1.
- `phpunit/phpunit` updated to `^10.2` and `laminas/laminas-coding-standard` to `^2.5`.
- Added missing return types, and solved code consistency issues.
- Removed the `suggest` section from `composer.json`.
- README update.

### Added

- PHP 8.2 support.
- Psalm static analysis, and code style check.

### Deprecated

- Nothing

### Removed

- PHP 7.4 and 8.0 support.

### Fixed

- Nothing

## 4.0.0 - 2022-09-19

### Changed

- Added new feature to inject doctrine repositories

### Added

- AnnotatedRepositoryFactory.php

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 3.2.1 - 2022-07-01

### Changed

- Downgraded package doctrine/cache to v1 to fix conflict

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Conflict for doctrine/common v2 (requires doctrine/cache v1)

## 3.2.0 - 2022-04-29

### Changed

- Added PHP 8.0 and 8.1 compatibility

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 3.1.0 - 2020-11-02

### Changed

- Composer 2 compatibility.
- Update composer.json to require version 2 of laminas/laminas-dependency-plugin to be Composer 2 compatible.

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 3.0.2 - 2020-04-23

Updated keywords

### Added

- Everything

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 3.0.1 - 2020-04-22

Updated phpunit and php_codesniffer package versions

### Added

- Everything

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 3.0.0 - 2020-04-12

Updated packages and php versions

### Added

- Everything

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 2.0.0 - 2020-01-30

### Changed

- Laminas and Mezzio migration.

### Added

- Support

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 1.1.1 - 2018-05-03

### Changed

- updated required dependencies

### Added

- Nothing

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 1.1.0 - 2017-03-15

### Changed

- updated factories to use PSR11 container

### Added

- documentation in readme file

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 1.0.1 - 2017-03-10

### Changed

- Nothing

### Added

- Updated php file headers doc blocks, updated package description

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing

## 1.0.0 - 2017-03-07

Initial tagged release

### Added

- Everything

### Deprecated

- Nothing

### Removed

- Nothing

### Fixed

- Nothing
