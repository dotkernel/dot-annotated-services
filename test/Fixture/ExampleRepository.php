<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Annotation\Entity;

/**
 * @Entity(name="DotTest\AnnotatedServices\Fixture\ExampleEntity")
 * @extends EntityRepository<ExampleEntity>
 */
class ExampleRepository extends EntityRepository
{
}
