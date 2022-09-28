<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

class StorageKeyNotSupportedException extends \RuntimeException implements InvalidArgumentThrowableInterface
{
    private StorageKeyInterface $storageKey;

    public function __construct(StorageKeyInterface $storageKey, int $code, ?\Throwable $previous = null)
    {
        $this->storageKey = $storageKey;
        $message = \sprintf('The given storage key by type "%s" is not supported', \get_class($storageKey));

        parent::__construct($message, $code, $previous);
    }

    public function getStorageKey(): StorageKeyInterface
    {
        return $this->storageKey;
    }
}
