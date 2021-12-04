<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use RuntimeException;

class CreateException extends RuntimeException
{
    public function __construct(int $code, ?\Throwable $throwable = null)
    {
        parent::__construct('Creating failed', $code, $throwable);
    }
}
