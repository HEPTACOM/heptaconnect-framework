<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;

/**
 * Central service to generate, serialize and deserialize storage keys.
 */
abstract class StorageKeyGeneratorContract
{
    /**
     * Generate a storage specific implementations of keys specified by their interface.
     *
     * @see StorageKeyInterface
     *
     * @psalm-param class-string<StorageKeyInterface> $keyClassName
     *
     * @throws UnsupportedStorageKeyException
     *
     * @return StorageKeyInterface[]
     * @psalm-return iterable<int, StorageKeyInterface>
     */
    abstract public function generateKeys(string $keyClassName, int $count): iterable;

    /**
     * Convert a storage key into a string.
     * Must be reversible by passing the result into deserialize.
     *
     * @throws UnsupportedStorageKeyException
     */
    public function serialize(StorageKeyInterface $key): string
    {
        if ($key instanceof PreviewPortalNodeKey) {
            try {
                return \json_encode($key, \JSON_THROW_ON_ERROR);
            } catch (\Throwable $throwable) {
                throw new UnsupportedStorageKeyException(\get_class($key), $throwable);
            }
        }

        throw new UnsupportedStorageKeyException(\get_class($key));
    }

    /**
     * Convert a string into a storage key.
     * Must be reversible by passing the result into serialize.
     *
     * @throws UnsupportedStorageKeyException
     */
    public function deserialize(string $keyData): StorageKeyInterface
    {
        try {
            $json = (array) \json_decode($keyData, true, 3, \JSON_THROW_ON_ERROR);

            $portalType = (string) ($json['preview'] ?? null);

            if (\is_a($portalType, PortalContract::class, true)) {
                return new PreviewPortalNodeKey($portalType);
            }
        } catch (\Throwable $throwable) {
            throw new UnsupportedStorageKeyException(StorageKeyInterface::class, $throwable);
        }

        throw new UnsupportedStorageKeyException(StorageKeyInterface::class);
    }
}
