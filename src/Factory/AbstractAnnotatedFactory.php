<?php
/**
 * @see https://github.com/dotkernel/dot-annotated-services/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-annotated-services/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\AnnotatedServices\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;
use Psr\Container\ContainerInterface;

/**
 * Class AbstractAnnotatedFactory
 * @package Dot\AnnotatedServices\Factory
 */
abstract class AbstractAnnotatedFactory
{
    const CACHE_SERVICE = 'Dot\AnnotatedServices\Cache';

    /** @var  Reader */
    protected $annotationReader;

    /**
     * @param Reader $annotationReader
     */
    public function setAnnotationReader(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ContainerInterface $container
     * @return AnnotationReader|CachedReader|Reader
     */
    protected function createAnnotationReader(ContainerInterface $container): Reader
    {
        if ($this->annotationReader !== null) {
            return $this->annotationReader;
        }

        AnnotationRegistry::registerLoader('class_exists');

        if (!$container->has(AbstractAnnotatedFactory::CACHE_SERVICE)) {
            return $this->annotationReader = new AnnotationReader();
        } else {
            /** @var Cache $cache */
            $cache = $container->get(AbstractAnnotatedFactory::CACHE_SERVICE);
            $debug = false;
            if ($container->has('config')) {
                $config = $container->get('config');
                if (isset($config['debug'])) {
                    $debug = (bool)$config['debug'];
                }
            }
            return $this->annotationReader = new CachedReader(new AnnotationReader(), $cache, $debug);
        }
    }
}
