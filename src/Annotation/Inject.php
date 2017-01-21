<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 1/20/2017
 * Time: 4:37 PM
 */

namespace Dot\AnnotatedServices\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Inject
 * @package Dot\AnnotatedServiced\Annotation
 * @Annotation
 * @Target({"METHOD"})
 */
class Inject
{
    /** @var  array */
    private $services;

    public function __construct(array $values)
    {
        $this->services = isset($values['value']) ? $values['value'] : [];
    }

    /**
     * @return array|mixed
     */
    public function getServices()
    {
        return $this->services;
    }
}
