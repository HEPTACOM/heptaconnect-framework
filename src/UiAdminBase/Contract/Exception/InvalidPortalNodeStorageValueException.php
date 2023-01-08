<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class InvalidPortalNodeStorageValueException extends \RuntimeException
{
    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private string $storageKey,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct(\sprintf('Value of storage key "%s" has an invalid type', $this->storageKey), $code, $previous);
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getStorageKey(): string
    {
        return $this->storageKey;
    }
}
