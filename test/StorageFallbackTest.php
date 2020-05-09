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
            $this->fail('Method is implemented or does not throw exception');
        } catch (StorageMethodNotImplemented $exception) {
            $this->assertEquals('getConfiguration', $exception->getMethod());
            $this->assertEquals(Fixture\FallbackedStorage::class, $exception->getClass());
            $this->assertNull($exception->getPrevious());
            $this->assertStringContainsString($exception->getMethod(), $exception->getMessage());
            $this->assertStringContainsString($exception->getClass(), $exception->getMessage());
            $this->assertEquals(0, $exception->getCode());
        }
    }

    public function testSetConfigurationFallback(): void
    {
        $storage = new Fixture\FallbackedStorage();

        try {
            $storage->setConfiguration('', []);
            $this->fail('Method is implemented or does not throw exception');
        } catch (StorageMethodNotImplemented $exception) {
            $this->assertEquals('setConfiguration', $exception->getMethod());
            $this->assertEquals(Fixture\FallbackedStorage::class, $exception->getClass());
            $this->assertNull($exception->getPrevious());
            $this->assertStringContainsString($exception->getMethod(), $exception->getMessage());
            $this->assertStringContainsString($exception->getClass(), $exception->getMessage());
            $this->assertEquals(0, $exception->getCode());
        }
    }
}
