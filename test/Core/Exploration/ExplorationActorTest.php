<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Emission\Contract\EmissionActorInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitContextFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder;
use Heptacom\HeptaConnect\Core\Exploration\ExplorationActor;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map\MappingMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map\MappingMapResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Mapping;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Mapping\MappingMapActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @covers \Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder
 * @covers \Heptacom\HeptaConnect\Core\Exploration\ExplorationActor
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map\MappingMapPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map\MappingMapResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Mapping
 */
class ExplorationActorTest extends TestCase
{
    public function testDirectEmission(): void
    {
        $logger = new NullLogger();
        $emissionActor = $this->createMock(EmissionActorInterface::class);
        $emitContextFactory = $this->createMock(EmitContextFactoryInterface::class);
        $publisher = $this->createMock(PublisherInterface::class);
        $emitterStackBuilderFactory = $this->createMock(EmitterStackBuilderFactoryInterface::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $mappingMapAction = $this->createMock(MappingMapActionInterface::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $mappingNodeKey = $this->createMock(MappingNodeKeyInterface::class);
        $emitterStackBuilder = new EmitterStackBuilder(new EmitterCollection(), FooBarEntity::class, $logger);
        $entity1 = new FooBarEntity();
        $entity2 = new FooBarEntity();
        $entity3 = new FooBarEntity();
        $actor = new ExplorationActor(
            $logger,
            $emissionActor,
            $emitContextFactory,
            $publisher,
            $emitterStackBuilderFactory,
            $storageKeyGenerator,
            $mappingMapAction
        );

        $entity1->setPrimaryKey('385deb43270148f5b69ebce7f927b3fd');
        $entity2->setPrimaryKey('7ef582d040dd49ea8902a0d66901ba0e');
        $entity3->setPrimaryKey('efd00370fdb9407aaff0cd36de8ea881');
        $capturedIds = [];
        $capturedPortalNodeKey = null;

        $context->method('getPortalNodeKey')->willReturn($portalNodeKey);
        $emitterStackBuilderFactory->method('createEmitterStackBuilder')->willReturn($emitterStackBuilder);
        $stack->expects(static::once())->method('next')->willReturn([$entity1, $entity2, $entity3]);
        $mappingMapAction->expects(static::exactly(1))->method('map')->willReturnCallback(static function (MappingMapPayload $payload) use ($mappingNodeKey, &$capturedIds, &$capturedPortalNodeKey) {
            $capturedPortalNodeKey = $payload->getPortalNodeKey();
            $result = new MappedDatasetEntityCollection();

            /** @var DatasetEntityContract $entity */
            foreach ($payload->getEntityCollection() as $entity) {
                $capturedIds[] = $entity->getPrimaryKey();
                $result->push([new MappedDatasetEntityStruct(
                    new Mapping(
                        $entity->getPrimaryKey(),
                        $payload->getPortalNodeKey(),
                        $mappingNodeKey,
                        \get_class($entity)
                    ),
                    $entity
                )]);
            }

            return new MappingMapResult($result);
        });

        $actor->performExploration(FooBarEntity::class, $stack, $context);

        static::assertSame($capturedPortalNodeKey, $portalNodeKey);
        static::assertSame([
            $entity1->getPrimaryKey(),
            $entity2->getPrimaryKey(),
            $entity3->getPrimaryKey(),
        ], $capturedIds);
    }

    public function testExploration(): void
    {
        $logger = new NullLogger();
        $emissionActor = $this->createMock(EmissionActorInterface::class);
        $emitContextFactory = $this->createMock(EmitContextFactoryInterface::class);
        $publisher = $this->createMock(PublisherInterface::class);
        $emitterStackBuilderFactory = $this->createMock(EmitterStackBuilderFactoryInterface::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $mappingMapAction = $this->createMock(MappingMapActionInterface::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $mappingNodeKey = $this->createMock(MappingNodeKeyInterface::class);
        $emitterStackBuilder = new EmitterStackBuilder(new EmitterCollection(), FooBarEntity::class, $logger);
        $actor = new ExplorationActor(
            $logger,
            $emissionActor,
            $emitContextFactory,
            $publisher,
            $emitterStackBuilderFactory,
            $storageKeyGenerator,
            $mappingMapAction
        );

        $entityId1 = '385deb43270148f5b69ebce7f927b3fd';
        $entityId2 = '7ef582d040dd49ea8902a0d66901ba0e';
        $entityId3 = 'efd00370fdb9407aaff0cd36de8ea881';
        $capturedIds = [];
        $capturedPortalNodeKey = null;

        $context->method('getPortalNodeKey')->willReturn($portalNodeKey);
        $emitterStackBuilderFactory->method('createEmitterStackBuilder')->willReturn($emitterStackBuilder);
        $stack->expects(static::once())->method('next')->willReturn([$entityId1, $entityId2, $entityId3]);
        $mappingMapAction->expects(static::exactly(1))->method('map')->willReturnCallback(static function (MappingMapPayload $payload) use ($mappingNodeKey, &$capturedIds, &$capturedPortalNodeKey) {
            $capturedPortalNodeKey = $payload->getPortalNodeKey();
            $result = new MappedDatasetEntityCollection();

            /** @var DatasetEntityContract $entity */
            foreach ($payload->getEntityCollection() as $entity) {
                $capturedIds[] = $entity->getPrimaryKey();
                $result->push([new MappedDatasetEntityStruct(
                    new Mapping(
                        $entity->getPrimaryKey(),
                        $payload->getPortalNodeKey(),
                        $mappingNodeKey,
                        \get_class($entity)
                    ),
                    $entity
                )]);
            }

            return new MappingMapResult($result);
        });

        $actor->performExploration(FooBarEntity::class, $stack, $context);

        static::assertSame($capturedPortalNodeKey, $portalNodeKey);
        static::assertSame([
            $entityId1,
            $entityId2,
            $entityId3,
        ], $capturedIds);
    }
}
