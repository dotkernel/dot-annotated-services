<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Exception;

use function sprintf;

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    public static function classNotFound(string $requestedName): self
    {
        return new self(sprintf(
            'Defined injectable service "%s" could not be found in container or as a class.',
            $requestedName
        ));
    }

    public static function doesNotExtend(string $class): self
    {
        return new self(sprintf('Class has to extend "%s".', $class));
    }

    public static function annotationNotFound(string $annotation, string $class, string $factory): self
    {
        return new self(sprintf(
            'You need to use the "%s" annotation in "%s" class so that the "%s" can create it.',
            $annotation,
            $class,
            $factory
        ));
    }

    public static function invalidAnnotation(string $requestedName): self
    {
        return new self(sprintf(
            'Annotated factories can only be used with services that are identified by their FQCN. '
            . 'Provided "%s" service name is not a valid class.',
            $requestedName
        ));
    }
}
