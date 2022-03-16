<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

class ReadException extends \Exception
{
    public function __construct(string $what, int $code, ?\Throwable $previous = null)
    {
        parent::__construct('Failed to read ' . $what, $code, $previous);
    }
}
