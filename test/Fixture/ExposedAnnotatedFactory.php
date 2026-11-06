<?php

declare(strict_types=1);

namespace DotTest\AnnotatedServices\Fixture;

use Doctrine\Common\Annotations\Reader;
use Dot\AnnotatedServices\Factory\AbstractAnnotatedFactory;
use Psr\Container\ContainerInterface;

/**
 * Exposes the protected reader factory method so it can be tested directly.
 */
class ExposedAnnotatedFactory extends AbstractAnnotatedFactory
{
    public function reader(ContainerInterface $container): Reader
    {
        return $this->createAnnotationReader($container);
    }
}
