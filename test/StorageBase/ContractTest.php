<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract
 */
final class ContractTest extends TestCase
{
    public function testExtendingStorageKeyGenerator(): void
    {
        $this->expectNotToPerformAssertions();
        new class() extends StorageKeyGeneratorContract {
            public function generateKeys(string $keyClassName, int $count): iterable
            {
                while ($count-- > 0) {
                    yield new class() implements StorageKeyInterface {
                        public function equals(StorageKeyInterface $other): bool
                        {
                            return false;
                        }

                        #[\ReturnTypeWillChange]
                        public function jsonSerialize()
                        {
                            return null;
                        }
                    };
                }
            }

            public function serialize(StorageKeyInterface $key): string
            {
                return '';
            }

            public function deserialize(string $keyData): StorageKeyInterface
            {
                return \iterable_to_array($this->generateKeys($keyData, 1))[0];
            }
        };
    }
}
