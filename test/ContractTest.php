<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\ConfigurationStorageContract;
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
        };
    }
}
