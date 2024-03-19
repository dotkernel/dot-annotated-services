# Inject entity repositories


## Prepare repository

`dot-annotated-services` determines the entity a repository is related to by looking at the `#[Entity]` attribute, added to the repository class.

```php
<?php

declare(strict_types=1);

namespace YourApp\Repository;

#[Dot\AnnotatedServices\Attribute\Entity(name: YourApp\Entity\Example::class)]
class ExampleRepository extends Doctrine\ORM\EntityRepository
{
}
```

Each entity repository must extend `Doctrine\ORM\EntityRepository`.


## Register repository

Open the ConfigProvider of the module where your repository resides.

Add a new entry under `factories`, where the key is your repository FQCN and the value is `Dot\AnnotatedServices\Factory\AttributedRepositoryFactory::class`.

See below example for a better understanding of the file structure.

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
                YourApp\Repository\ExampleRepository::class => Dot\AnnotatedServices\Factory\AttributedRepositoryFactory::class,
            ],
        ];
    }
}
```
