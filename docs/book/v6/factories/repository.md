# Inject entity repositories

## Prepare repository

`dot-annotated-services` determines the entity a repository is related to by looking at the `@Entity` annotation, added to the repository class.

```php
<?php

declare(strict_types=1);

namespace YourApp\Repository;

/**
 * @Dot\AnnotatedServices\Annotation\Entity(name="YourApp\Entity\Example")
 */
class ExampleRepository extends Doctrine\ORM\EntityRepository
{
}
```

Each entity repository must extend `Doctrine\ORM\EntityRepository`.

The entity must declare this class as its repository, so that `EntityManager::getRepository()` returns an instance of it:

```php
#[Doctrine\ORM\Mapping\Entity(repositoryClass: YourApp\Repository\ExampleRepository::class)]
class Example
{
}
```

If the entity manager returns a repository that is not an instance of the registered class, `AnnotatedRepositoryFactory` throws a `Dot\AnnotatedServices\Exception\RuntimeException`.

## Register repository

Open the ConfigProvider of the module where your repository resides.

Add a new entry under `factories`, where the key is your repository FQCN and the value is `Dot\AnnotatedServices\Factory\AnnotatedRepositoryFactory::class`.

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
                YourApp\Repository\ExampleRepository::class => Dot\AnnotatedServices\Factory\AnnotatedRepositoryFactory::class,
            ],
        ];
    }
}
```
