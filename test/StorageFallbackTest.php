<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented
 * @covers \Heptacom\HeptaConnect\Storage\Base\Support\StorageFallback
 */
class StorageFallbackTest extends TestCase
{
    /**
     * @dataProvider provideFallbackedMethods
     */
    public function testMethodFallback(string $method, array $args): void
    {
        $storage = new Fixture\FallbackedStorage();

        try {
            static::assertTrue(\method_exists($storage, $method));
            /* @phpstan-ignore-next-line VariableMethodCallRule */
            $storage->{$method}(...$args);
            static::fail('Method is implemented or does not throw exception');
        } catch (StorageMethodNotImplemented $exception) {
            static::assertEquals($method, $exception->getMethod());
            static::assertEquals(Fixture\FallbackedStorage::class, $exception->getClass());
            static::assertNull($exception->getPrevious());
            static::assertStringContainsString($exception->getMethod(), $exception->getMessage());
            static::assertStringContainsString($exception->getClass(), $exception->getMessage());
            static::assertEquals(0, $exception->getCode());
        }
    }

    public function provideFallbackedMethods(): iterable
    {
        /* @psalm-suppress MixedInferredReturnType */
        yield ['setConfiguration', ['', []]];
        yield ['getConfiguration', ['']];
        yield ['createMappingNodes', [[]]];
        yield ['getMapping', ['', '']];
        yield ['createMappings', [new MappingCollection()]];
        yield ['getRouteTargets', ['', '']];
        yield ['addRouteTarget', ['', '', '']];
    }
}
