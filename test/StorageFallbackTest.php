<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented
 * @covers \Heptacom\HeptaConnect\Storage\Base\Support\StorageFallback
 */
class StorageFallbackTest extends TestCase
{
    public function testGetConfigurationFallback(): void
    {
        $storage = new Fixture\FallbackedStorage();

        try {
            $storage->getConfiguration('');
            static::fail('Method is implemented or does not throw exception');
        } catch (StorageMethodNotImplemented $exception) {
            static::assertEquals('getConfiguration', $exception->getMethod());
            static::assertEquals(Fixture\FallbackedStorage::class, $exception->getClass());
            static::assertNull($exception->getPrevious());
            static::assertStringContainsString($exception->getMethod(), $exception->getMessage());
            static::assertStringContainsString($exception->getClass(), $exception->getMessage());
            static::assertEquals(0, $exception->getCode());
        }
    }

    public function testSetConfigurationFallback(): void
    {
        $storage = new Fixture\FallbackedStorage();

        try {
            $storage->setConfiguration('', []);
            static::fail('Method is implemented or does not throw exception');
        } catch (StorageMethodNotImplemented $exception) {
            static::assertEquals('setConfiguration', $exception->getMethod());
            static::assertEquals(Fixture\FallbackedStorage::class, $exception->getClass());
            static::assertNull($exception->getPrevious());
            static::assertStringContainsString($exception->getMethod(), $exception->getMessage());
            static::assertStringContainsString($exception->getClass(), $exception->getMessage());
            static::assertEquals(0, $exception->getCode());
        }
    }
}
