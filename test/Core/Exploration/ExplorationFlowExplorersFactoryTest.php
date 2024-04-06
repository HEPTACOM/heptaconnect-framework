<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Emission\Contract\EmitContextFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackProcessorInterface;
use Heptacom\HeptaConnect\Core\Exploration\Contract\DirectEmissionFlowEmittersFactoryInterface;
use Heptacom\HeptaConnect\Core\Exploration\DirectEmittingExplorer;
use Heptacom\HeptaConnect\Core\Exploration\EmissionJobDispatchingExplorer;
use Heptacom\HeptaConnect\Core\Exploration\ExplorationFlowExplorersFactory;
use Heptacom\HeptaConnect\Core\Exploration\IdentityMappingExplorer;
use Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract;
use Heptacom\HeptaConnect\Core\Job\Transition\Contract\ExploredPrimaryKeysToJobsConverterInterface;
use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\AbstractBufferedResultProcessingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Exploration\DirectEmitter
 * @covers \Heptacom\HeptaConnect\Core\Exploration\DirectEmittingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Exploration\EmissionJobDispatchingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Exploration\ExplorationFlowExplorersFactory
 * @covers \Heptacom\HeptaConnect\Core\Exploration\IdentityMappingExplorer
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 */
final class ExplorationFlowExplorersFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $directEmissionEmittersFactory = $this->createMock(DirectEmissionFlowEmittersFactoryInterface::class);
        $directEmissionEmittersFactory
            ->expects(static::once())
            ->method('createEmitters')
            ->willReturn(new EmitterCollection());

        $factory = new ExplorationFlowExplorersFactory(
            $directEmissionEmittersFactory,
            $this->createMock(EmitterStackBuilderFactoryInterface::class),
            $this->createMock(EmitterStackProcessorInterface::class),
            $this->createMock(EmitContextFactoryInterface::class),
            $this->createMock(ExploredPrimaryKeysToJobsConverterInterface::class),
            $this->createMock(JobDispatcherContract::class),
            new PrimaryKeyToEntityHydrator(),
            $this->createMock(IdentityMapActionInterface::class),
            $this->createMock(LoggerInterface::class),
            1,
            1,
            1
        );
        $emitters = $factory->createExplorers(
            $this->createMock(PortalNodeKeyInterface::class),
            FooBarEntity::class()
        );

        static::assertCount(3, $emitters);
        static::assertInstanceOf(EmissionJobDispatchingExplorer::class, $emitters[0]);
        static::assertInstanceOf(IdentityMappingExplorer::class, $emitters[1]);
        static::assertInstanceOf(DirectEmittingExplorer::class, $emitters[2]);
    }
}
