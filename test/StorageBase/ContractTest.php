<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeySerializerContract;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StorageKeySerializerContract::class)]
final class ContractTest extends TestCase
{
    public function testExtendingStorageKeyGenerator(): void
    {
        $this->expectNotToPerformAssertions();
        new class() extends StorageKeySerializerContract {
            #[\Override]
            public function serialize(StorageKeyInterface $key): string
            {
                return '';
            }

            #[\Override]
            public function deserialize(string $keyData): StorageKeyInterface
            {
                return new class($keyData) implements StorageKeyInterface {
                    public function __construct(
                        private readonly string $key,
                    ) {
                    }

                    #[\Override]
                    public function equals(StorageKeyInterface $other): bool
                    {
                        return false;
                    }

                    #[\Override]
                    public function jsonSerialize(): string
                    {
                        return $this->key;
                    }
                };
            }
        };
    }
}
