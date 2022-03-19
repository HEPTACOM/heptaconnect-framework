<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use PHPUnit\Framework\TestCase;

final class ContractTest extends TestCase
{
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
