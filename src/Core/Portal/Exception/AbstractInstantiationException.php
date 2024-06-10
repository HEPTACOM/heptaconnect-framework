<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Portal\Exception;

abstract class AbstractInstantiationException extends \RuntimeException
{
    /**
     * @param class-string $class
     */
    public function __construct(
        private readonly string $class,
        ?\Throwable $previous = null
    ) {
        parent::__construct('Could not instantiate object', 0, $previous);
    }

    /**
     * @phpstan-return class-string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
