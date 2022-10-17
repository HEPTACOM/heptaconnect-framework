<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Parallelization\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

class ResourceIsLockedException extends \RuntimeException
{
    private string $resourceKey;

    public function __construct(string $resourceKey, private ?\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface $owner, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('The resource "%s" is locked', $resourceKey), 0, $previous);
        $this->resourceKey = $resourceKey;
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }

    public function getOwner(): ?StorageKeyInterface
    {
        return $this->owner;
    }
}
