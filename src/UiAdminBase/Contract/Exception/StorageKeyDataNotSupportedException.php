<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

class StorageKeyDataNotSupportedException extends \RuntimeException implements InvalidArgumentThrowableInterface
{
    private string $keyData;

    public function __construct(string $keyData, int $code, ?\Throwable $previous = null)
    {
        $this->keyData = $keyData;
        $message = \sprintf('The given key data "%s" is not supported for storage keys', $keyData);

        parent::__construct($message, $code, $previous);
    }

    public function getKeyData(): string
    {
        return $this->keyData;
    }
}
