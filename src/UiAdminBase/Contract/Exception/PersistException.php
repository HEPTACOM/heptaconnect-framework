<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

final class PersistException extends \LogicException
{
    public function __construct(int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The operation could not persist data', $code, $previous);
    }
}
