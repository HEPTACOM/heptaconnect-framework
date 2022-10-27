<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Exception;

class UnexpectedFormatOfUriException extends \InvalidArgumentException
{
    private string $argument;

    private string $format;

    public function __construct(string $argument, string $format, int $code, ?\Throwable $previous = null)
    {
        $message = \sprintf('The given argument "%s" does not match the URI format "%s"', $argument, $format);
        parent::__construct($message, $code, $previous);
        $this->argument = $argument;
        $this->format = $format;
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
