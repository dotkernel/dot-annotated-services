<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<ExampleEntity>
 */
class RepositoryWithoutAnnotation extends EntityRepository
{
}
