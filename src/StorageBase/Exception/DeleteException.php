<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

class DeleteException extends \RuntimeException
{
    public function __construct(int $code, ?\Throwable $throwable = null)
    {
        parent::__construct('Deleting failed', $code, $throwable);
    }
}
