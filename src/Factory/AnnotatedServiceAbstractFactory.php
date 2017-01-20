<?php
/**
 * @copyright: DotKernel
 * @library: dot-annotated-services
 * @author: n3vrax
 * Date: 1/21/2017
 * Time: 12:05 AM
 */

namespace Dot\AnnotatedServiced\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class AnnotatedServiceAbstractFactory
 * @package Dot\AnnotatedServiced\Factory
 */
class AnnotatedServiceAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (! class_exists($requestedName)) {
            return false;
        }
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // TODO: Implement __invoke() method.
    }
}
