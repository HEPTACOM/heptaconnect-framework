<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\ClassString\Exception;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
class UnexpectedLeadingNamespaceSeparatorInClassNameException extends \InvalidArgumentException
{
    private readonly string $className;

    public function __construct(string $className, int $code, ?\Throwable $previous = null)
    {
        $message = \sprintf('Given value "%s" starts with a namespace separator', $className);
        parent::__construct($message, $code, $previous);
        $this->className = $className;
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
