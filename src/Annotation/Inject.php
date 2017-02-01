<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-rbac
 * @author: n3vrax
 * Date: 1/20/2017
 * Time: 4:37 PM
 */

declare(strict_types = 1);

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
    private $services = [];

    /**
     * Inject constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->services = isset($values['value']) ? $values['value'] : [];
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
