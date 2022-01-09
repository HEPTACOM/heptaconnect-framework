<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

class UnsupportedStorageKeyException extends \Exception
{
    private string $storageKeyClass;

    public function __construct(string $storageKeyClass, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('Unsupported storage key class: %s', $storageKeyClass), 0, $previous);
        $this->storageKeyClass = $storageKeyClass;
    }

    public function getStorageKeyClass(): string
    {
        return $this->storageKeyClass;
    }
}
