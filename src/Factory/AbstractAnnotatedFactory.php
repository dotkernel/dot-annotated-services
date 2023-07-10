<?php

/**
 * @see https://github.com/dotkernel/dot-annotated-services/ for the canonical source repository
 */

declare(strict_types=1);

namespace Dot\AnnotatedServices\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class AbstractAnnotatedFactory
{
    protected const CACHE_SERVICE = 'Dot\AnnotatedServices\Cache';

    /** @var  Reader */
    protected $annotationReader;

    public function setAnnotationReader(Reader $annotationReader): void
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function createAnnotationReader(ContainerInterface $container): Reader
    {
        if ($this->annotationReader !== null) {
            return $this->annotationReader;
        }

        AnnotationRegistry::registerLoader('class_exists');

        if (! $container->has(self::CACHE_SERVICE)) {
            return $this->annotationReader = new AnnotationReader();
        } else {
            /** @var Cache $cache */
            $cache = $container->get(self::CACHE_SERVICE);
            $debug = false;
            if ($container->has('config')) {
                $config = $container->get('config');
                if (isset($config['debug'])) {
                    $debug = (bool) $config['debug'];
                }
            }
            return $this->annotationReader = new CachedReader(new AnnotationReader(), $cache, $debug);
        }
    }
}
