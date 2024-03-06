<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\TestData;

use Doctrine\ORM\EntityRepository;
use Dot\AnnotatedServices\Attribute\Entity;
use DotTest\AnnotatedServices\TestData\Entity as TestEntity;

/**
 * @extends EntityRepository<object>
 */
#[Entity(name: TestEntity::class)]
class Repository extends EntityRepository
{
}
