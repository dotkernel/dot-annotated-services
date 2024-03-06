<?php

declare(strict_types=1);

namespace Dot\AnnotatedServices\Exception;

use function sprintf;

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    public const MESSAGE_ATTRIBUTE_NOT_FOUND =
        'You need to use the "%s" attribute on the "%s" class so that "%s" can create it.';
    public const MESSAGE_CLASS_NOT_FOUND     =
        'Defined injectable "%s" could not be found in container or as a class.';
    public const MESSAGE_DOES_NOT_EXTEND     =
        'Class "%s" must extend class "%s".';
    public const MESSAGE_RECURSIVE_INJECT    =
        'Class "%s" can not be injected into itself.';

    public static function classNotFound(string $requestedName): self
    {
        return new self(sprintf(self::MESSAGE_CLASS_NOT_FOUND, $requestedName));
    }

    public static function doesNotExtend(string $requestedName, string $class): self
    {
        return new self(sprintf(self::MESSAGE_DOES_NOT_EXTEND, $requestedName, $class));
    }

    public static function attributeNotFound(string $attribute, string $class, string $factory): self
    {
        return new self(sprintf(self::MESSAGE_ATTRIBUTE_NOT_FOUND, $attribute, $class, $factory));
    }

    public static function recursiveInject(string $requestedName): self
    {
        return new self(sprintf(self::MESSAGE_RECURSIVE_INJECT, $requestedName));
    }
}
