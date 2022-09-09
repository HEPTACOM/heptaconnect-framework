<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

final class ReadException extends \LogicException
{
    public function __construct(int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The operation failed at reading data', $code, $previous);
    }
}
