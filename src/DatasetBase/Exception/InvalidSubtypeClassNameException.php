<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Exception;

class InvalidSubtypeClassNameException extends \InvalidArgumentException
{
    private string $givenClassName;

    /**
     * @var class-string
     */
    private string $expectedSuperClassName;

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
            'Given value "%s" is neither extending nor implementing "%"',
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
