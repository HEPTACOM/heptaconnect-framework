<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\ConfigurationStorageContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\PortalStorageContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Storage\Base\Contract\ConfigurationStorageContract
 */
class ContractTest extends TestCase
{
    public function testExtendingConfigurationStorage(): void
    {
        $this->expectNotToPerformAssertions();
        new class() extends ConfigurationStorageContract {
            public function getConfiguration(PortalNodeKeyInterface $portalNodeKey): array
            {
                return [];
            }

            public function setConfiguration(PortalNodeKeyInterface $portalNodeKey, ?array $data): void
            {
            }
        };
    }

    public function testExtendingPortalStorage(): void
    {
        $this->expectNotToPerformAssertions();
        new class() extends PortalStorageContract {
            public function set(
                PortalNodeKeyInterface $portalNodeKey,
                string $key,
                string $value,
                string $type,
                ?\DateInterval $ttl = null
            ): void {
            }

            public function unset(PortalNodeKeyInterface $portalNodeKey, string $key): void
            {
            }

            public function getValue(PortalNodeKeyInterface $portalNodeKey, string $key): string
            {
                return '';
            }

            public function getType(PortalNodeKeyInterface $portalNodeKey, string $key): string
            {
                return '';
            }

            public function list(PortalNodeKeyInterface $portalNodeKey): iterable
            {
                return [];
            }

            public function has(PortalNodeKeyInterface $portalNodeKey, string $key): bool
            {
                return false;
            }

            public function clear(PortalNodeKeyInterface $portalNodeKey): void
            {
            }

            public function getMultiple(PortalNodeKeyInterface $portalNodeKey, array $keys): array
            {
                return [];
            }

            public function deleteMultiple(PortalNodeKeyInterface $portalNodeKey, array $keys): void
            {
            }
        };
    }

    public function testExtendingStorageKeyGenerator(): void
    {
        $this->expectNotToPerformAssertions();
        new class() extends StorageKeyGeneratorContract {
            public function generateKey(string $keyClassName): StorageKeyInterface
            {
                return new class() implements StorageKeyInterface {
                    public function equals(StorageKeyInterface $other): bool
                    {
                        return false;
                    }

                    public function jsonSerialize()
                    {
                        return null;
                    }
                };
            }

            public function generateKeys(string $keyClassName, int $count): iterable
            {
                yield from [];
            }

            public function serialize(StorageKeyInterface $key): string
            {
                return '';
            }

            public function deserialize(string $keyData): StorageKeyInterface
            {
                return $this->generateKey($keyData);
            }
        };
    }
}
