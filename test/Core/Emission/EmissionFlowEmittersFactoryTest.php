<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\EmissionFlowEmittersFactory;
use Heptacom\HeptaConnect\Core\Emission\ReceiveJobDispatchingEmitter;
use Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract;
use Heptacom\HeptaConnect\Core\Job\Transition\Contract\EmittedEntitiesToJobsConverterInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Emission\AbstractBufferedResultProcessingEmitter
 * @covers \Heptacom\HeptaConnect\Core\Emission\EmissionFlowEmittersFactory
 * @covers \Heptacom\HeptaConnect\Core\Emission\ReceiveJobDispatchingEmitter
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 */
final class EmissionFlowEmittersFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $factory = new EmissionFlowEmittersFactory(
            $this->createMock(EmittedEntitiesToJobsConverterInterface::class),
            $this->createMock(JobDispatcherContract::class),
            1
        );
        $emitters = $factory->createEmitters(
            $this->createMock(PortalNodeKeyInterface::class),
            FooBarEntity::class()
        );

        static::assertCount(1, $emitters);
        static::assertInstanceOf(ReceiveJobDispatchingEmitter::class, $emitters[0]);
    }
}
