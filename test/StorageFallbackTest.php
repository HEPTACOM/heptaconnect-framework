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
    public function testConfigurationGetFallback(): void
    {
        $storage = new Fixture\FallbackedStorage();

        try {
            $storage->configurationGet('');
            $this->fail('Method is implemented or does not throw exception');
        } catch (StorageMethodNotImplemented $exception) {
            $this->assertEquals('configurationGet', $exception->getMethod());
            $this->assertEquals(Fixture\FallbackedStorage::class, $exception->getClass());
            $this->assertNull($exception->getPrevious());
            $this->assertStringContainsString($exception->getMethod(), $exception->getMessage());
            $this->assertStringContainsString($exception->getClass(), $exception->getMessage());
            $this->assertEquals(0, $exception->getCode());
        }
    }

    public function testConfigurationSetFallback(): void
    {
        $storage = new Fixture\FallbackedStorage();

        try {
            $storage->configurationSet('', []);
            $this->fail('Method is implemented or does not throw exception');
        } catch (StorageMethodNotImplemented $exception) {
            $this->assertEquals('configurationSet', $exception->getMethod());
            $this->assertEquals(Fixture\FallbackedStorage::class, $exception->getClass());
            $this->assertNull($exception->getPrevious());
            $this->assertStringContainsString($exception->getMethod(), $exception->getMessage());
            $this->assertStringContainsString($exception->getClass(), $exception->getMessage());
            $this->assertEquals(0, $exception->getCode());
        }
    }
}
