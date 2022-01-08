<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

class CreateException extends \RuntimeException
{
    public function __construct(int $code, ?\Throwable $throwable = null)
    {
        parent::__construct('Creating failed', $code, $throwable);
    }
}
