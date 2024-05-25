<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

class StorageKeyNotSupportedException extends \RuntimeException implements InvalidArgumentThrowableInterface
{
    public function __construct(
        private readonly StorageKeyInterface $storageKey,
        int $code,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('The given storage key by type "%s" is not supported', $storageKey::class);

        parent::__construct($message, $code, $previous);
    }

    public function getStorageKey(): StorageKeyInterface
    {
        return $this->storageKey;
    }
}
