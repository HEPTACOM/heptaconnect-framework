<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\ClassString\Exception;

class InvalidSubtypeClassNameException extends \InvalidArgumentException
{
    private readonly string $givenClassName;

    /**
     * @var class-string
     */
    private readonly string $expectedSuperClassName;

    /**
     * @param class-string $expectedSuperClassName
     */
    public function __construct(
        string $givenSuperClassName,
        string $expectedSuperClassName,
        int $code,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf(
            'Given value "%s" is neither extending nor implementing "%s"',
            $givenSuperClassName,
            $expectedSuperClassName
        );
        parent::__construct($message, $code, $previous);
        $this->givenClassName = $givenSuperClassName;
        $this->expectedSuperClassName = $expectedSuperClassName;
    }

    public function getGivenClassName(): string
    {
        return $this->givenClassName;
    }

    /**
     * @return class-string
     */
    public function getExpectedSuperClassName(): string
    {
        return $this->expectedSuperClassName;
    }
}
