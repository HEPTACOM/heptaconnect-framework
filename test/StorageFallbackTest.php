<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
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
        yield ['setConfiguration', [$this->createMock(PortalNodeKeyInterface::class), []]];
        yield ['getConfiguration', [$this->createMock(PortalNodeKeyInterface::class)]];
        yield ['createMappingNodes', [[], $this->createMock(PortalNodeKeyInterface::class)]];
        yield ['getMapping', [
            $this->createMock(MappingNodeKeyInterface::class),
            $this->createMock(PortalNodeKeyInterface::class),
        ]];
        yield ['createMappings', [new MappingCollection()]];
        yield ['getRouteTargets', [$this->createMock(PortalNodeKeyInterface::class), '']];
        yield ['createRouteTarget', [
            $this->createMock(PortalNodeKeyInterface::class),
            $this->createMock(PortalNodeKeyInterface::class),
            '',
        ]];
    }
}
