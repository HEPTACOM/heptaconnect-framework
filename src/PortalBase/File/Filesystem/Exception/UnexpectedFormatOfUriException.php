<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Exception;

class UnexpectedFormatOfUriException extends \InvalidArgumentException
{
    public function __construct(
        private string $argument,
        private string $format,
        int $code,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The given argument "%s" does not match the URI format "%s"', $this->argument, $this->format);
        parent::__construct($message, $code, $previous);
    }

    public function getArgument(): string
    {
        return $this->argument;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
