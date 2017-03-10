<?php
/**
 * @see https://github.com/dotkernel/dot-annotated-services/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-annotated-services/blob/master/LICENSE.md MIT License
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
