<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\IdentityMappingExplorer;
use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\IdentityMappingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Exploration\AbstractBufferedResultProcessingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\DependencyTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult
 */
final class IdentityMappingExplorerTest extends TestCase
{
    public function testMapsPksInBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $batchSize = 3;

        $identityMapAction
            ->expects(static::exactly(5))
            ->method('map')
            ->willReturn(new IdentityMapResult(new MappedDatasetEntityCollection()))
            ->with(static::callback($this->validateMapPayloadCount($batchSize)));

        $explorer = new IdentityMappingExplorer($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(
            \array_map('strval', \array_keys(\array_fill(0, $batchSize * 5, 0))),
            []
        );

        \iterable_to_array($explorer->explore($context, $stack));
    }

    public function testConvertsRemainingPksIfPkCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $batchSize = 3;

        $container->method('get')->willReturnCallback(fn (string $id) => [
            LoggerInterface::class => $this->createMock(LoggerInterface::class),
        ][$id] ?? null);
        $context->method('getContainer')->willReturn($container);
        $identityMapAction
            ->expects(static::exactly(6))
            ->method('map')
            ->willReturn(new IdentityMapResult(new MappedDatasetEntityCollection()))
            ->withConsecutive([
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount(2)),
            ]);

        $explorer = new IdentityMappingExplorer($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(
            \array_map('strval', \array_keys(\array_fill(0, $batchSize * 5 + 2, 0))),
            []
        );

        \iterable_to_array($explorer->explore($context, $stack));
    }

    public function testConvertsPksToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $container = $this->createMock(ContainerInterface::class);
        $portalNodeLogger = $this->createMock(LoggerInterface::class);
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $batchSize = 3;

        $container->method('get')->willReturnCallback(fn (string $id) => [
            LoggerInterface::class => $portalNodeLogger,
        ][$id] ?? null);
        $context->method('getContainer')->willReturn($container);
        $identityMapAction
            ->expects(static::exactly(2))
            ->method('map')
            ->willReturn(new IdentityMapResult(new MappedDatasetEntityCollection()))
            ->withConsecutive([
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount(2)),
            ]);
        $portalNodeLogger->expects(static::once())->method('critical');

        $explorer = new IdentityMappingExplorer($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($batchSize): iterable {
            yield from \array_map('strval', \array_keys(\array_fill(0, $batchSize + 2, 0)));

            throw new \RuntimeException('Test message');
        });

        \iterable_to_array($explorer->explore($context, $stack));
    }

    private function validateMapPayloadCount(int $batchSize): \Closure
    {
        return static function (IdentityMapPayload $payload) use ($batchSize): bool {
            static::assertCount($batchSize, $payload->getEntityCollection());

            return true;
        };
    }
}
