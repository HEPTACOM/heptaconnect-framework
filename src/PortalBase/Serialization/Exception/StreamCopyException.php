<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Serialization\Exception;

class StreamCopyException extends \RuntimeException
{
    public function __construct(int $code, ?\Throwable $previous = null)
    {
        parent::__construct('Copying stream failed', $code, $previous);
    }
}
